<?php

namespace Modularity\Module\Posts\Helper;

class GetPosts
{
    public function getPosts(array $fields)
    {
        /**
         * Filter the posts before they are fetched. Return anything other than
         * null to skip the default behavior.
         * @param array|null $posts
         * @param array $fields
         * @return array|null
         */
        $posts = apply_filters('Modularity/Module/Posts/Helper/pre_getPosts', null, $fields);
        if(!is_null($posts)) {
            return $posts;
        }
        $posts = (array) get_posts($this->getPostArgs($fields));
        if (!empty($posts)) {
            foreach ($posts as &$post) {
                $data['taxonomiesToDisplay'] = !empty($fields['taxonomy_display']) ? $fields['taxonomy_display'] : [];
                $data = apply_filters("Modularity/Module/Posts/Helper/getPosts/data", $data, $fields, $post);

                if (class_exists('\Municipio\Helper\Post')) {
                    if (in_array($fields['posts_display_as'], ['expandable-list'])) {
                        $post = \Municipio\Helper\Post::preparePostObject($post);
                    } else {
                        $post = \Municipio\Helper\Post::preparePostObjectArchive($post, $data);
                    }

                    if (!empty($post->schemaData['place']['pin'])) {
                        $post->attributeList['data-js-map-location'] = json_encode($post->schemaData['place']['pin']);
                    }
                }
            }
        } else {
            $posts = [];
        }
        
        /**
         * Filter the posts after they are fetched.
         * @param array $posts
         * @param array $fields
         * @return array
         */
        $posts = apply_filters('Modularity/Module/Posts/Helper/getPosts', $posts, $fields);
        return $posts;
    }

    private function getPostArgs(array $fields)
    {
        $metaQuery  = false;
        $taxQuery = [];
        $orderby    = !empty($fields['posts_sort_by']) ? $fields['posts_sort_by'] : 'date';
        $order      = !empty($fields['posts_sort_order']) ? $fields['posts_sort_order'] : 'desc';

        // Get post args
        $getPostsArgs = [
            'post_type' => 'any',
            'post_password' => false,
            'suppress_filters' => false
        ];

        // Sort by meta key
        if (strpos($orderby, '_metakey_') === 0) {
            $orderby_key = substr($orderby, strlen('_metakey_'));
            $orderby = 'order_clause';
            $metaQuery = [
                [
                    'relation' => 'OR',
                    'order_clause' => [
                        'key' => $orderby_key,
                        'compare' => 'EXISTS'
                    ],
                    [
                        'key' => $orderby_key,
                        'compare' => 'NOT EXISTS'
                    ]
                ]
            ];
        }

        if ($orderby != 'false') {
            $getPostsArgs['order'] = $order;
            $getPostsArgs['orderby'] = $orderby;
        }

        // Post statuses
        $getPostsArgs['post_status'] = ['publish', 'inherit'];
        if (is_user_logged_in()) {
            $getPostsArgs['post_status'][] = 'private';
        }

        // Taxonomy filter
        if (
            isset($fields['posts_taxonomy_filter']) &&
            $fields['posts_taxonomy_filter'] === true &&
            !empty($fields['posts_taxonomy_type'])
        ) {
            $taxType = $fields['posts_taxonomy_type'];
            $taxValues = (array)$fields['posts_taxonomy_value'];

            foreach ($taxValues as $term) {
                $taxQuery[] = [
                    'taxonomy' => $taxType,
                    'field' => 'slug',
                    'terms' => $term
                ];
            }
        }

        // Add new taxonomy filtering logic
        if (!empty($fields['mod_posts_filtering'])) {
            foreach ($fields['mod_posts_filtering'] as $filter) {
                $taxQuery[] = [
                    'taxonomy' => $filter['taxonomy'],
                    'field' => 'id',
                    'terms' => $filter["term_{$filter['taxonomy']}"],
                    'operator' => $filter['operator'],
                ];
            }
        }

        // Apply the modularity filter hook for customizations
        $taxQuery = apply_filters('modularity/mod_posts_tax_query', $taxQuery, $fields, $this->ID ?? null);

        if (!empty($taxQuery)) {
            $getPostsArgs['tax_query'] = $taxQuery;
        }

        // Meta filter
        if (isset($fields['posts_meta_filter']) && $fields['posts_meta_filter'] === true) {
            $metaQuery[] = [
                'key' => $fields['posts_meta_key'] ?? '',
                'value' => [$fields['posts_meta_value'] ?? ''],
                'compare' => 'IN',
            ];
        }

        // Data source
        switch ($fields['posts_data_source']) {
            case 'posttype':
                $getPostsArgs['post_type'] = $fields['posts_data_post_type'];
                if ($currentPostID = $this->getCurrentPostID()) {
                    $getPostsArgs['post__not_in'] = [
                        $currentPostID
                    ];
                }
                break;

            case 'children':
                $getPostsArgs['post_type'] = get_post_type();
                $getPostsArgs['post_parent'] = $fields['posts_data_child_of'];
                break;

            case 'manual':
                $getPostsArgs['post__in'] = $fields['posts_data_posts'];
                if ($orderby == 'false') {
                    $getPostsArgs['orderby'] = 'post__in';
                }
                break;
        }

        // Add metaquery to args
        if ($metaQuery) {
            $getPostsArgs['meta_query'] = $metaQuery;
        }

        // Number of posts
        if (isset($fields['posts_count']) && is_numeric($fields['posts_count'])) {
            $getPostsArgs['posts_per_page'] = $fields['posts_count'];
        }

        return $getPostsArgs;
    }

    public function getCurrentPostID()
    {
        global $post;
        if (isset($post->ID) && is_numeric($post->ID)) {
            return $post->ID;
        }
        return false;
    }
}

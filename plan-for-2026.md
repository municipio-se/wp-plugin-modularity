# Plan for LTS 2026

Bas: `6.16.5`. Nuvarande LTS-head: `9db17be9`. Ny upstream-bas:
`wp-theme-municipio` `7.7.18`, katalogen `Modularity/`.

## Slutsats

Den separata pluginforken bör inte följa med som egen plugin när LTS flyttar
till `wp-theme-municipio` `7.7.18`. Däremot behöver flera LTS-ändringar
porteras smalt in i temats inbäddade `Modularity/`.

## Arbetsplan

- [ ] Ta bort separat plugin ur LTS-bundlet när temat går till `7.7.18`.
- [ ] Säkerställ Composer-strategin om något fortfarande kräver
      `helsingborg-stad/modularity` eller `municipio/wp-plugin-modularity`.
- [ ] Porta säkerhetsfixarna till `wp-theme-municipio/Modularity/`.
- [ ] Porta sökindexfixarna för hidden modules och `post_content_filtered`.
- [ ] Gör en användningsaudit av LTS-specifika `Modularity/...`-hooks.
- [ ] Återskapa bara de hooks som är verkliga kund- eller integrationskontrakt.
- [ ] Använd upstreams nya Posts-, ManualInput-, asset- och servicearkitektur
      som bas.

## Beslutstabell

| Område | Vår slutändring | Upstream-läge | Bedömning | Berörda commits |
| --- | --- | --- | --- | --- |
| Separat plugin och Composer | Bytte till `municipio/wp-plugin-modularity`, GPL, installer-name och bantad beroendeyta. | `7.7.18` autoloadar `Modularity\\` från temat och har ingen separat Modularity-plugin. | Släpp som separat plugin; hantera eventuella Composer-krav i tema/metapaket | `aaefc7f7`, `b97f4bf4`, `dc7e33c5`, `820b5732`, `df106c65`, `0b1971b1`, `209c84a2` |
| Bootstrap, språk och ACF | Lade MU-plugin-textdomain, plugin-local bootstrap och fallback när ACF saknas. | Temat startar Modularity på `after_setup_theme`, använder serviceklasser och ACF-export med textdomain `municipio`. | Ersätt med temats bootstrap; verifiera översättningsdomän | `8fc8b9b0`, `734dc660` |
| Sökindexering | Renderade moduler till `post_content_filtered`, rättade sökresultaträkning och uteslöt dolda moduler. | Temats `Search.php` saknar LTS-fixen för `post_content_filtered` och hoppar inte över hidden-moduler vid indexing. | Återskapa smalare | `ffdda6b5`, `a38fe1cc`, `512c227f`, `f7cdbf8c` |
| Posts-modul och arkivlänkar | Lade filter för template controller, archive URL, pre/getPosts, taxonomifiltrering, template-element och archive-link visibility. | Temat har ny Posts-arkitektur med schema/multisite/private controllers, pagination och archive-link style/icon, men flera LTS-filter saknas. | Ersätt query/UX med upstream; återskapa publika filter som används | `2bc1da64`, `f8c6b4ac`, `d41f7143`, `07e40f6d`, `b39932ac`, `3439aefc`, `ad4eb381`, `8d5047db`, `fbd8bd25`, `320b8716`, `7474b756` |
| Publika extension-hooks | Lade `Modularity/Module/Template`, `Modularity/Display/modules`, `Modularity/Display/pre_outputModule`, `Modularity/Display/BeforeModule::widthClass`, `Modularity/Editor/getModule`, `Modularity/Module/ManualInput/data/item` och flera module-specifika filter. | Flera av dessa saknas i temats `Modularity/`; vissa ersätts av nyare hooks som `Modularity/Module/Posts/template`, `Modularity/Module/ManualInput/Template` och `Modularity/Module/TemplatePath`. | Återskapa smalare efter användningsaudit | `d89fd740`, `7e4844b4`, `bc2f0837`, `00143786`, `ccf0fc53`, `bcf9adda`, `419a65cb`, `46faa69c`, `c5ce13c9`, `df106c65`, `ce0b7af6` |
| Manual input | Bytte länkfält, rättade länkrendering och lade item-/templatefilter. | Temat har nyare manual-input-stöd med länkar, knappar, eyebrow, custom background och privata moduler, men saknar LTS item-filter och generella module-template-filter. | Ersätt datamodell; återskapa filter om de är kontrakt | `87f5bd71`, `1f644c79`, `4e373c1a`, `7e4844b4`, `df106c65`, `ce0b7af6` |
| Video och Mediaflow | Lade Mediaflow-stöd och `Modularity/Display/mod-video/pre_getEmbedMarkup`. | Temats video-modul har nyare typning och asset-hantering, men saknar pre-embed-hooken och Mediaflow-anpassningen. | Behåll/återskapa smalare | `8c2e77cd`, `7819f9ee`, `1ed33b6e`, `2b89a8fe`, `700880b2` |
| Säkerhet | Lade nonce-verifiering, GET/POST-sanitization, sidebar option-sanitization, säkrare current URL, escapad adminlänk och CSP-attribut på inline scripts. | Temat täcker vissa delar, men saknar bland annat REST-nonce, `Ajax`-ID-sanitization, `Editor` sidebar option-sanitization, säkrare `currentUrl()` och flera `wp_inline_script_attributes`. | Behåll/återskapa smalare med hög prioritet | `2dc92d27`, `a3f1c11e`, `6b379ade`, `6adc1c88`, `73471466`, `83f43279`, `4f872bac` |
| Mindre modulbeteenden | Lade WebP/ikonstöd, contact-card-justering, show-more-a11y, text box color, RSS-länkfixar och FilesList-templatefunktion. | Temat täcker en del via nyare komponenter och FileList/RSS-refaktorer, men text box color och vissa template hooks saknar tydlig motsvarighet. | Ersätt där upstream täcker; verifiera text box color, RSS-länkar och FilesList-template | `af174a50`, `50118854`, `fe61bb33`, `7c155add`, `3dc7c5d5`, `6ec67235`, `996a73c5`, `bc2f0837` |
| Bygg, workflows, språkfiler och vendor | Tog bort workflows, bytte buildflöde och uppdaterade dist/language-filer. | Temat har egen Vite-/assetstruktur och inbäddade byggartefakter under `Modularity/assets/dist`. | Ej relevant som manuell port | workflow-/asset-/languagecommits |

## Risker att verifiera

- Kundkod kan lyssna på LTS-specifika `Modularity/...`-filter som saknas i
  temat.
- Dolda moduler riskerar att indexeras i sök om LTS-fixen inte porteras.
- REST-/Ajax-/Editor-säkerhetsfixar behöver kodgranskas i temat innan release.
- Översättningsdomänen flyttar från `modularity` till `municipio`; språkfiler
  måste hanteras med ordinarie verktyg vid implementation.
- Metapaketet får inte både ladda separat plugin och temats inbäddade
  `Modularity/`.

## Analyskommandon

- `git diff --stat 6.16.5..HEAD`
- `git log --reverse --format='%h%x09%ad%x09%s' --date=short 6.16.5..HEAD`
- `git log --reverse --format='%h%x09%ad%x09%s' --date=short 4.25.0..7.7.18 -- Modularity`
- `git diff --no-index --stat` mellan exporterad `6.16.5` och
  `7.7.18:Modularity/`
- `git diff --no-index --stat` mellan exporterad LTS-head och
  `7.7.18:Modularity/`
- Riktade `git diff --no-index`, `git diff`, `git grep` och `rg` för
  bootstrap, Composer, `Search`, `Display`, `Editor`, `Ajax`, REST API,
  `Posts`, `ManualInput`, `Video`, `Text`, `FilesList`, `Rss` och publika
  hooks.

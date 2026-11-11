(() => {
  let active = null;

  // wpLink is shared with TinyMCE and other ACF fields. Bind before ACF's
  // per-opening handlers so the original title is restored before ACF saves.
  jQuery(document).on("wplink-open.manualInput", () => {
    const node = window.acf?.wpLink?.get("node")?.[0];
    if (!node?.closest('[data-key="field_64ff232ad91ba"]')) return;

    const input = document.getElementById("wp-link-text");
    const row = input?.closest(".wp-link-text-field");
    if (!row) return;

    active = {
      input,
      row,
      display: row.style.display,
      title: window.acf.wpLink.getNodeValue().title,
    };
    row.style.display = "none";
    document.getElementById("wp-link-url")?.focus();
  });

  jQuery(document).on("wplink-close.manualInput", () => {
    if (!active) return;
    // Selecting an internal page can overwrite the hidden text input.
    // Keep the stored title even when changing the destination.
    active.input.value = active.title;
    active.row.style.display = active.display;
    active = null;
  });
})();

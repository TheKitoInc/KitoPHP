function blk_html_body_onload (body) {
  if (window.name != '') {
    try { parent.blk_html_body_onload(body) } catch (ex) {}
  }

  blk_html_preload_url = false
}

let blk_html_preload_url = false
function blk_html_a_onclick (link) {
  if (link.target != '' && link.target != '_self') {
    return true
  }

  if (blk_html_preload_url == false || blk_html_preload_url != link.href) {
    blk_html_preload_url = link.href
    window.open(link.href + '&Mode=Preload', blk_html_frame_name)
  }

  return false
}

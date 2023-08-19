// reporte de estado desde el modulo
function update_form_element (target, token, name, value, res) {
  if (target != '_self') {
    return parent.update_form_element('_self', token, name, value, res)
  }

  form = blk_get_form2(token)
  element = blk_get_element(form, name)

  element_base = blk_get_element(form, name + '_BASE')
  if (element_base != null) {
    element_base.value = value
  } else {
    element.value = value
  }

  blk_element_status(element.id, res)

  return true
}

// cambia contenido
function blk_element_change (element) {
  t = document.getElementById(element.id + '_BASE')
  if (t != null && t.value == element.value) {
    blk_element_status(element.id, '')
  }
}

// pierde foco
function blk_form_change (element) {
  t = document.getElementById(element.id + '_BASE')
  f = blk_get_form(element)

  if (t != null && t.value != element.value) {
    blk_element_status(element.id, 'L')

    // f.target=f.name+"I";
    // alert(f.target);
    return blk_submit_form(f)
  } else {
    return false
  }
}

// Cambia el span MSG con imagen y mensaje segun estado y elemento
function blk_element_status (element, status) {
  obj = document.getElementById(element)
  msg = document.getElementById(element + '_MSG')

  if (obj != null) {
    obj.readonly = status == 'L'
  }

  if (msg == null) {} else if (status == '') {
    msg.innerHTML = ''
  } else if (status == 'Y') {
    msg.innerHTML = "<img width=20 height=20 src='?Module=Form&Tag=Image&Image=ok.png'>"
  } else if (status == 'N') {
    msg.innerHTML = "<img width=20 height=20 src='?Module=Form&Tag=Image&Image=error.jpg'>"
  } else if (status == 'L') {
    msg.innerHTML = "<img width=20 height=20 src='?Module=Form&Tag=Image&Image=load.gif'>"
  } else {
    msg.innerHTML = "<img width=20 height=20 src='?Module=Form&Tag=Image&Image=warn.jpg'>" + status
  }

  if (status == 'Y') {
    window.setTimeout("blk_element_status('" + element + "','');", 10000)
  }
}
// Envia formulario
function blk_submit_form (form) {
  f.action = f.action + '&Target=' + f.target
  return form.submit()
}
// Obtiene el formulario en funcion de un elemento del mismo
function blk_get_form (element) {
  for (i = 0; i < document.forms.length; i++) {
    f = document.forms[i]

    for (ii = 0; ii < f.elements.length; ii++) {
      e = f.elements[ii]
      if (e.id == element.id) {
        return f
      }
    }
  }
  return null
}
// Obtiene el formulario en funcion del token
function blk_get_form2 (token) {
  for (i = 0; i < document.forms.length; i++) {
    f = document.forms[i]

    for (ii = 0; ii < f.elements.length; ii++) {
      e = f.elements[ii]

      if (e.name == 'blk_form_token' && e.value == token) {
        return f
      }
    }
  }
  return null
}
// Obtiene el elemnto en funcion del form y nombre
function blk_get_element (form, name) {
  for (ii = 0; ii < form.elements.length; ii++) {
    e = form.elements[ii]

    if (e.name == name) {
      return e
    }
  }

  return null
}

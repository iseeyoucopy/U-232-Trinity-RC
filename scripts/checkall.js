var checkflag = 'false'
var marked_row = []

function check (field) {
  if (checkflag == 'false') {
    for (i = 0; i < field.length; i++) {
      field[i].checked = true
    }
    checkflag = 'true'
  } else {
    for (i = 0; i < field.length; i++) {
      field[i].checked = false
    }
    checkflag = 'false'
  }
}
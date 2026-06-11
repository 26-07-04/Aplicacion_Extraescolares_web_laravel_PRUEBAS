<script>
window.swalAlerta = function (mensaje, titulo) {
  var msg = mensaje == null ? '' : String(mensaje);
  var title = titulo || 'Atención';
  var text = msg;

  if (!titulo && msg.indexOf('Error:') === 0) {
    title = 'Error';
    text = msg.replace(/^Error:\s*/, '');
  }

  if (typeof Swal !== 'undefined') {
    Swal.fire({
      title: title,
      text: text,
      icon: 'question'
    });
    return;
  }

  alert(msg);
};
</script>

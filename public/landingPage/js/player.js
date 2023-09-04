  var video = videojs('my-video', {
    autoplay: true,
    loop: true,
    muted: true,
  });

  var overlay = document.getElementById('overlay');
  var isMuted = true;

  overlay.addEventListener('click', function() {
    if (isMuted) {
      video.muted(false);  // Ativar o som
      overlay.style.opacity = '0'; // Esconder a imagem
      isMuted = false;
    } else {
      video.muted(true);  // Desativar o som
      overlay.style.opacity = '1'; // Exibir a imagem
      isMuted = true;
    }
  });

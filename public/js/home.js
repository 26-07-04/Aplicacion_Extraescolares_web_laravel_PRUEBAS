// Pequeños comportamientos para la UI del home
document.addEventListener('DOMContentLoaded', function(){
  const navToggle = document.getElementById('navToggle');
  const mainNav = document.getElementById('mainNav');
  if(navToggle && mainNav){
    navToggle.addEventListener('click', function(){
      if(mainNav.style.display === 'flex' || mainNav.style.display === ''){
        mainNav.style.display = (mainNav.style.display === 'flex') ? 'none' : 'flex';
      } else {
        mainNav.style.display = 'flex';
      }
    });
    // cerrar al cambiar tamaño
    window.addEventListener('resize', function(){
      if(window.innerWidth > 900) mainNav.style.display = 'flex';
      if(window.innerWidth <= 900) mainNav.style.display = 'none';
    });
  }
});

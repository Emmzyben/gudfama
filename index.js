document.addEventListener("DOMContentLoaded", function(event) { 
    let currentWriteupIndex = 0;
    const writeups = document.querySelectorAll('.writeup');
    
    function showNextWriteup() {
      const currentWriteup = writeups[currentWriteupIndex];
      currentWriteup.classList.remove('active');
      currentWriteupIndex = (currentWriteupIndex + 1) % writeups.length;
      const nextWriteup = writeups[currentWriteupIndex];
      nextWriteup.classList.add('active');
    }
  
    setInterval(showNextWriteup, 6000);
  });

  function toggleFAQ(id) {
    var div = document.getElementById('div' + id);
    div.classList.toggle('show');
  }
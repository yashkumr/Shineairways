
  
  let currentSlide = 0;
 
  window.addEventListener("DOMContentLoaded", (event) => {
    const prevBtn = document.getElementById("prev-btn");
    const nextBtn = document.getElementById("next-btn");
    const slider = document.querySelector(".slider");




    let interval = setInterval(function(){
  
     
    
      currentSlide++;
      if(currentSlide==12){
        currentSlide=0;       
      }
      slider.style.transform = `translateX(-${currentSlide * 15}%)`;
      slider.style.transition= `5s linear;`;
      
    },2000)

    
    
    
    nextBtn.addEventListener("click", () => {
      currentSlide++;
      if (currentSlide > 15) {
        currentSlide = 0;
        clearInterval(interval);
      }
      slider.style.transform = `translateX(-${currentSlide * 20}%)`;
    });
    
    prevBtn.addEventListener("click", () => {
      currentSlide--;
      if (currentSlide < 0) {
        currentSlide = 14;
        clearInterval(interval);
      }
      slider.style.transform = `translateX(-${currentSlide * 20}%)`;
    });
});





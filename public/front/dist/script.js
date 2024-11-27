  // Daftar gambar yang tersedia
  const images = [
    "../front/img/generator/ball-0.png",
    "../front/img/generator/ball-1.png",
    "../front/img/generator/ball-2.png",
    "../front/img/generator/ball-3.png",
    "../front/img/generator/ball-4.png",
    "../front/img/generator/ball-5.png",
    "../front/img/generator/ball-6.png",
    "../front/img/generator/ball-7.png",
    "../front/img/generator/ball-8.png",
    "../front/img/generator/ball-9.png",
  ];
  
  let count = 0;
  let count1 = 0;
  let count2 = 0;
  let count3 = 0;
  let count4 = 0;
  let count5 = 0;
  let intervalId;
  let intervalId1;
  let intervalId2;
  let intervalId3;
  let intervalId4;
  let intervalId5;
  let randomIndex1;
  let randomIndex2;
  let randomIndex3;
  let randomIndex4;
  let randomIndex5;
  

  function getRandomNumber(max) {
    return Math.floor(Math.random() * max);
  }
  
  function resetAll() {
    count = 0;
    count1 = 0;
    count2 = 0;
    count3 = 0;
    count4 = 0;
    count5 = 0;

    clearInterval(intervalId);
    clearInterval(intervalId1);
    clearInterval(intervalId2);
    clearInterval(intervalId3);
    clearInterval(intervalId4);
    clearInterval(intervalId5);
  }

  function displayRandomImages() {
resetAll();
 intervalId1 = setInterval(() => {
  document.getElementById("img1").src = images[count1 % 10];
  count1++;
  if (count1 >= 30 ) {
    clearInterval(intervalId1);
    displaySelectedNumber1(images);
  }
}, 100);

 intervalId2 = setInterval(() => {
  document.getElementById("img2").src = images[(count2+1) % 10];
  count2++;
  if (count2 >= 40) {
    clearInterval(intervalId2);
    displaySelectedNumber2(images);
  }
}, 100);

 intervalId3 = setInterval(() => {
  document.getElementById("img3").src = images[(count3+2) % 10];
  count3++;
  if (count3 >= 50) {
    clearInterval(intervalId3);
    displaySelectedNumber3(images);

  }
}, 100);

 intervalId4 = setInterval(() => {
  document.getElementById("img4").src = images[(count4+3) % 10];
  count4++;
  if (count4 >= 60) {
    clearInterval(intervalId4);
    displaySelectedNumber4(images);
  }
}, 100);

intervalId5 = setInterval(() => {
  document.getElementById("img5").src = images[(count5+3) % 10];
  document.getElementById("img6").src = images[(count5+3) % 10];
  count5++;
  if (count5 >= 62) {
    clearInterval(intervalId5);
    displaySelectedNumber(images);
  }

},100);
}

  function displaySelectedNumber1(images) {

   randomIndex1 = getRandomNumber(images.length);
  count1 = 0;
  intervalId1 = setInterval(() => {
    document.getElementById("img1").src = images[randomIndex1];

    count1++;
    if (count1 >= 30) {
      clearInterval(intervalId1);
    }
  }, 100);

};
function displaySelectedNumber2(images) {

   randomIndex2 = getRandomNumber(images.length);
  count2 = 0;
  intervalId2 = setInterval(() => {
    document.getElementById("img2").src = images[randomIndex2];

    count2++;
    if (count2 >= 40) {
      clearInterval(intervalId2);
      resolve(randomIndex2);
    }
  }, 100);

};
function displaySelectedNumber3(images) {

// return new Promise(resolve => {
   randomIndex3 = getRandomNumber(images.length);
  count3 = 0;
  intervalId3 = setInterval(() => {
    document.getElementById("img3").src = images[randomIndex3];

    count3++;
    if (count3 >= 50) {
      clearInterval(intervalId3);
      resolve(randomIndex3);
    }
  }, 100);

};
function displaySelectedNumber4(images) {

// return new Promise(resolve => {
   randomIndex4 = getRandomNumber(images.length);
  count4 = 0;
  intervalId4 = setInterval(() => {
    document.getElementById("img4").src = images[randomIndex4];
    count4++;
    if (count4 >= 60) {
      clearInterval(intervalId4);
      resolve(randomIndex4);
    }
  }, 100);

};
  

  function displaySelectedNumber(images) {
    resetAll();
    const randomIndex5 = getRandomNumber(images.length);
    const randomIndex6 = getRandomNumber(images.length);
    count5 = 0;
    intervalId = setInterval(() => {
      document.getElementById("img7").src = images[randomIndex1];
      document.getElementById("img8").src = images[randomIndex3];
      document.getElementById("img9").src = images[randomIndex1];
      document.getElementById("img10").src = images[randomIndex4];
      document.getElementById("img11").src = images[randomIndex5];
      document.getElementById("img12").src = images[randomIndex1];
      document.getElementById("img13").src = images[randomIndex6];
      document.getElementById("img14").src = images[randomIndex1];
      document.getElementById("img15").src = images[randomIndex2];
      document.getElementById("img16").src = images[randomIndex2];
      document.getElementById("img17").src = images[randomIndex3];
      document.getElementById("img18").src = images[randomIndex4];
      document.getElementById("img19").src = images[randomIndex5];
      document.getElementById("img20").src = images[randomIndex2];
      document.getElementById("img21").src = images[randomIndex2];
      document.getElementById("img22").src = images[randomIndex6];
      document.getElementById("img23").src = images[randomIndex3];
      document.getElementById("img24").src = images[randomIndex4];
      document.getElementById("img25").src = images[randomIndex5];
      document.getElementById("img26").src = images[randomIndex3];
      document.getElementById("img27").src = images[randomIndex3];
      document.getElementById("img28").src = images[randomIndex6];
      document.getElementById("img29").src = images[randomIndex4];
      document.getElementById("img30").src = images[randomIndex5];
      document.getElementById("img31").src = images[randomIndex6];
      document.getElementById("img32").src = images[randomIndex4];
      document.getElementById("img33").src = images[randomIndex6];
      document.getElementById("img34").src = images[randomIndex5];
      document.getElementById("img35").src = images[randomIndex1];
      document.getElementById("img36").src = images[randomIndex2];
      document.getElementById("img37").src = images[randomIndex1];
      document.getElementById("img38").src = images[randomIndex2];
      document.getElementById("img39").src = images[randomIndex3];
      document.getElementById("img40").src = images[randomIndex4];
      document.getElementById("img41").src = images[randomIndex5];
      document.getElementById("img42").src = images[randomIndex6];


      count5++;
      if (count5 >= 62) {
        clearInterval(intervalId);
      }
    }, 100);
  }
  
  function rotateImages() {
    displayRandomImages();
  }

  const generateButton = document.getElementById("generate");
  generateButton.addEventListener("click", rotateImages);
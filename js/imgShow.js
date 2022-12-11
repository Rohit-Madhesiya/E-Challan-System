// Here window.onload is using because js is not loading into the DOM and caught type error
window.onload = function () {
  camImg = document.getElementById("camImg");
  vehImg = document.getElementById("vehImg");
  inptImg = document.getElementById("inptImg");
  inptImg.addEventListener("change", payImgShow);
}


function payImgShow() {
  const file = this.files[0];

  if (file) {
    reader = new FileReader();
    camImg.style.display = "none";
    reader.addEventListener("load", function () {
      vehImg.setAttribute("src", this.result);
    });
    reader.readAsDataURL(file);
  }
}






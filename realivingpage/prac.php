<canvas id="canvas"></canvas>
<img id="sourceImage" src="img/test.jpg" hidden />

<script>
  const img = document.getElementById("sourceImage");
  const canvas = document.getElementById("canvas");
  const ctx = canvas.getContext("2d");

  img.onload = () => {
    canvas.width = img.width;
    canvas.height = img.height;
    ctx.drawImage(img, 0, 0);
    
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const data = imageData.data;

    // Simple red overlay
    for (let i = 0; i < data.length; i += 4) {
      if (data[i] > 200 && data[i+1] > 200 && data[i+2] > 200) { // light area
        data[i] = 180; // red
        data[i+1] = 30; // green
        data[i+2] = 30; // blue
      }
    }

    ctx.putImageData(imageData, 0, 0);
  };
</script>

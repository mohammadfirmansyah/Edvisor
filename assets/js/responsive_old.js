function hideScrollbar() {
  document.documentElement.style.overflow = 'hidden'; // Hide scrollbars
}

function scaleBody() {
  const screenWidth = window.innerWidth;
  const screenHeight = window.innerHeight;
  const baseWidth = 1440;
  const baseHeight = 1024;
  const aspectRatio = screenWidth / screenHeight;

  // Check for aspect ratio or screen height greater than width
  if (aspectRatio <= baseWidth / baseHeight || screenHeight > screenWidth) {
      // Portrait orientation (e.g., mobile phones)
      const scale = screenHeight / baseHeight;
      document.body.style.transform = `scale(${scale})`;
      document.body.style.transformOrigin = 'top left';
      document.body.style.width = `${screenWidth / scale}px`;
      document.body.style.height = `${baseHeight}px`;
      document.documentElement.style.width = `${screenWidth / scale}px`;
      document.body.style.overflowY = 'hidden';
      document.body.style.overflowX = 'scroll';
  } else {
      // Landscape orientation (e.g., laptops)
      const scale = screenWidth / baseWidth;
      document.body.style.transform = `scale(${scale})`;
      document.body.style.transformOrigin = 'top left';
      document.body.style.width = `${baseWidth}px`;
      document.body.style.height = `${screenHeight / scale}px`;
      document.documentElement.style.height = `${screenHeight / scale}px`;
      document.body.style.overflowY = 'scroll';
      document.body.style.overflowX = 'hidden';
  }
}

window.addEventListener('resize', scaleBody);
window.addEventListener('load', function() {
  hideScrollbar();
  scaleBody();
});
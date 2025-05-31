// Get URL from the page
const url = window.pdfUrl;

let pdfDoc = null,
    pageNum = 1,
    pageIsRendering = false,
    pageNumIsPending = null,
    scale = 1.5,
    canvas = document.querySelector('#pdf-render'),
    ctx = canvas.getContext('2d'),
    textLayer = null;

// Render the page
const renderPage = num => {
  pageIsRendering = true;

  pdfDoc.getPage(num).then(page => {
    const viewport = page.getViewport({ scale });
    canvas.height = viewport.height;
    canvas.width = viewport.width;

    // Prepare canvas for rendering
    const renderContext = {
      canvasContext: ctx,
      viewport: viewport,
      textLayer: textLayer,
      renderInteractiveForms: true,
      enableScripting: true
    };

    // Create text layer div if not exists
    if (!textLayer) {
      textLayer = document.createElement('div');
      textLayer.className = 'textLayer';
      canvas.parentNode.insertBefore(textLayer, canvas.nextSibling);
    }

    // Clear previous text layer content
    textLayer.innerHTML = '';
    textLayer.style.width = canvas.style.width;
    textLayer.style.height = canvas.style.height;

    // Render page and create text layer
    const renderTask = page.render(renderContext);
    
    // Get text content and render text layer
    page.getTextContent().then(textContent => {
      pdfjsLib.renderTextLayer({
        textContent: textContent,
        container: textLayer,
        viewport: viewport,
        textDivs: []
      });
    });

    // Get annotations and render them
    page.getAnnotations().then(annotations => {
      annotations.forEach(annotation => {
        if (annotation.subtype === 'Highlight' || annotation.subtype === 'Text') {
          const rect = annotation.rect;
          const highlight = document.createElement('div');
          highlight.className = 'pdf-annotation';
          highlight.style.left = rect[0] + 'px';
          highlight.style.top = rect[1] + 'px';
          highlight.style.width = (rect[2] - rect[0]) + 'px';
          highlight.style.height = (rect[3] - rect[1]) + 'px';
          
          if (annotation.title) {
            highlight.setAttribute('title', annotation.title);
          }
          
          // Add hover event for popup
          if (annotation.contents) {
            highlight.addEventListener('mouseover', (e) => showTooltip(e, annotation.contents));
            highlight.addEventListener('mouseout', hideTooltip);
          }
          
          textLayer.appendChild(highlight);
        }
      });
    });

    renderTask.promise.then(() => {
      pageIsRendering = false;
      if (pageNumIsPending !== null) {
        renderPage(pageNumIsPending);
        pageNumIsPending = null;
      }
    });

    document.querySelector('#page-num').textContent = num;
  });
};

// Check for pages rendering
const queueRenderPage = num => {
  if (pageIsRendering) {
    pageNumIsPending = num;
  } else {
    renderPage(num);
  }
};

// Show Prev Page
const showPrevPage = () => {
  if (pageNum <= 1) {
    return;
  }
  pageNum--;
  queueRenderPage(pageNum);
};

// Show Next Page
const showNextPage = () => {
  if (pageNum >= pdfDoc.numPages) {
    return;
  }
  pageNum++;
  queueRenderPage(pageNum);
};

// Get Document
if (url) {
    pdfjsLib
        .getDocument(url)
        .promise.then(pdfDoc_ => {
            pdfDoc = pdfDoc_;
            document.querySelector('#page-count').textContent = pdfDoc.numPages;
            renderPage(pageNum);
        })
        .catch(err => {
            // Display error
            const div = document.createElement('div');
            div.className = 'error';
            div.appendChild(document.createTextNode(err.message));
            document.querySelector('body').insertBefore(div, canvas);
            // Remove top bar
            document.querySelector('.top-bar').style.display = 'none';
        });
} else {
    console.error('No PDF URL provided');
}

// Button Events
document.querySelector('#prev-page').addEventListener('click', showPrevPage);
document.querySelector('#next-page').addEventListener('click', showNextPage);

// Add tooltip functions
function showTooltip(event, content) {
  let tooltip = document.getElementById('pdf-tooltip');
  if (!tooltip) {
    tooltip = document.createElement('div');
    tooltip.id = 'pdf-tooltip';
    document.body.appendChild(tooltip);
  }
  
  tooltip.innerHTML = content;
  tooltip.style.left = (event.pageX + 10) + 'px';
  tooltip.style.top = (event.pageY + 10) + 'px';
  tooltip.style.display = 'block';
}

function hideTooltip() {
  const tooltip = document.getElementById('pdf-tooltip');
  if (tooltip) {
    tooltip.style.display = 'none';
  }
}

// Enable text selection
document.addEventListener('mousedown', (e) => {
  if (e.target.closest('.textLayer')) {
    e.stopPropagation();
  }
});
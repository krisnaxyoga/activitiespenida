// main.js
document.addEventListener('DOMContentLoaded', () => {
  /* ===== Mobile Menu Toggle ===== */
  const btn  = document.getElementById('mobile-menu-button');
  const menu = document.getElementById('mobile-menu');
  const iconOpen  = document.getElementById('menu-icon');
  const iconClose = document.getElementById('close-icon');

  if (!btn || !menu) return; // early exit kalau tidak ada

  const closeMenu = () => {
    menu.classList.add('hidden');
    iconOpen?.classList.remove('hidden');
    iconClose?.classList.add('hidden');
    menu.style.maxHeight = '';
  };

  const openMenu = () => {
    menu.classList.remove('hidden');
    iconOpen?.classList.add('hidden');
    iconClose?.classList.remove('hidden');
    menu.style.transition = 'max-height .3s ease-out';
    menu.style.maxHeight  = menu.scrollHeight + 'px';
  };

  btn.addEventListener('click', () => {
    menu.classList.contains('hidden') ? openMenu() : closeMenu();
  });

  /* Close saat klik link di dalam menu */
  menu.querySelectorAll('a').forEach(link =>
    link.addEventListener('click', closeMenu)
  );

  /* Close saat klik di luar */
  document.addEventListener('click', e => {
    if (!menu.contains(e.target) && !btn.contains(e.target)) closeMenu();
  });

  /* Reset saat resize ke desktop */
  window.addEventListener('resize', () => {
    if (window.innerWidth >= 768) closeMenu();
  });

  /* ===== Smooth Scroll (offset header) ===== */
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const target = document.querySelector(this.getAttribute('href'));
      if (!target) return;
      e.preventDefault();
      const offset = document.querySelector('header').offsetHeight + 20;
      window.scrollTo({ top: target.offsetTop - offset, behavior: 'smooth' });
    });
  });

  /* ===== Touch Feedback ===== */
  document.querySelectorAll('a, button').forEach(el => {
    el.addEventListener('touchstart', () => el.style.transform = 'scale(.98)');
    el.addEventListener('touchend',   () => el.style.transform = '');
  });

  /* ===== Lazy-Load Images (gunakan data-src) ===== */
  if ('IntersectionObserver' in window) {
    const imgObs = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          img.src = img.dataset.src;
          img.classList.remove('lazy');
          observer.unobserve(img);
        }
      });
    });
    document.querySelectorAll('img[data-src]').forEach(img => imgObs.observe(img));
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const toggles = document.querySelectorAll(".faq-toggle");

  toggles.forEach(btn => {
    btn.addEventListener("click", () => {
      const content = btn.nextElementSibling;
      const icon = btn.querySelector("svg");

      // Check if this content is currently open
      const isOpen = content.style.maxHeight && content.style.maxHeight !== "0px";

      // Close all other FAQ items (accordion behavior)
      document.querySelectorAll(".faq-content").forEach(el => {
        if (el !== content) {
          el.style.maxHeight = "0px";
          const otherIcon = el.previousElementSibling.querySelector("svg");
          if (otherIcon) otherIcon.classList.remove("rotate-180");
        }
      });

      // Toggle current item
      if (isOpen) {
        // Close this item
        content.style.maxHeight = "0px";
        icon?.classList.remove("rotate-180");
      } else {
        // Open this item
        content.style.maxHeight = content.scrollHeight + "px";
        icon?.classList.add("rotate-180");
      }
    });
  });

  /* ===== Table of Contents Generator ===== */
  function generateTableOfContents() {
    const content = document.getElementById('article-content');
    const tocNav = document.getElementById('toc-nav');
    const mobileTocNav = document.getElementById('mobile-toc-nav');
    const tocContainer = document.getElementById('table-of-contents');

    if (!content || !tocNav || !mobileTocNav) return;

    // Find all headings (h2, h3, h4)
    const headings = content.querySelectorAll('h2, h3, h4, h5, h6');

    if (headings.length === 0) {
      // Hide TOC if no headings found
      tocContainer.style.display = 'none';
      document.getElementById('mobile-toc-toggle').style.display = 'none';
      return;
    }

    let tocHTML = '';

    headings.forEach((heading, index) => {
      // Create unique ID for heading
      const id = `heading-${index}`;
      heading.id = id;

      // Get heading level for indentation
      const level = parseInt(heading.tagName.charAt(1));
      const indent = (level - 2) * 12; // 12px per level starting from h2

      // Create TOC link
      const text = heading.textContent.trim();
      tocHTML += `
        <a href="#${id}"
           class="block py-2 px-3 text-sm text-gray-600 hover:text-green-600 hover:bg-green-50 rounded transition-colors toc-link"
           style="margin-left: ${indent}px">
          ${text}
        </a>
      `;
    });

    // Insert TOC HTML into both desktop and mobile containers
    tocNav.innerHTML = tocHTML;
    mobileTocNav.innerHTML = tocHTML;

    // Add click handlers for smooth scrolling
    document.querySelectorAll('.toc-link').forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href').substring(1);
        const target = document.getElementById(targetId);

        if (target) {
          const headerOffset = 100; // Offset for fixed header
          const elementPosition = target.getBoundingClientRect().top;
          const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

          window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
          });

          // Close mobile TOC after clicking
          const mobileToc = document.getElementById('mobile-toc');
          if (!mobileToc.classList.contains('hidden')) {
            mobileToc.classList.add('hidden');
            document.getElementById('mobile-toc-icon').style.transform = 'rotate(0deg)';
          }

          // Update active link
          updateActiveTocLink(targetId);
        }
      });
    });
  }

  // Update active TOC link based on scroll position
  function updateActiveTocLink(activeId = null) {
    // Remove active class from all links
    document.querySelectorAll('.toc-link').forEach(link => {
      link.classList.remove('text-green-600', 'bg-green-50', 'border-l-2', 'border-green-600');
      link.classList.add('text-gray-600');
    });

    // If activeId provided, highlight that link
    if (activeId) {
      const activeLinks = document.querySelectorAll(`a[href="#${activeId}"]`);
      activeLinks.forEach(link => {
        if (link.classList.contains('toc-link')) {
          link.classList.remove('text-gray-600');
          link.classList.add('text-green-600', 'bg-green-50', 'border-l-2', 'border-green-600');
        }
      });
      return;
    }

    // Auto-detect active section based on scroll position
    const headings = document.querySelectorAll('#article-content h2, #article-content h3, #article-content h4, #article-content h5, #article-content h6');
    let activeHeading = null;

    headings.forEach(heading => {
      const rect = heading.getBoundingClientRect();
      if (rect.top <= 150) { // 150px from top
        activeHeading = heading;
      }
    });

    if (activeHeading) {
      const activeLinks = document.querySelectorAll(`a[href="#${activeHeading.id}"]`);
      activeLinks.forEach(link => {
        if (link.classList.contains('toc-link')) {
          link.classList.remove('text-gray-600');
          link.classList.add('text-green-600', 'bg-green-50', 'border-l-2', 'border-green-600');
        }
      });
    }
  }

  // Mobile TOC toggle
  const mobileTocToggle = document.getElementById('mobile-toc-toggle');
  const mobileToc = document.getElementById('mobile-toc');
  const mobileTocIcon = document.getElementById('mobile-toc-icon');

  if (mobileTocToggle && mobileToc && mobileTocIcon) {
    mobileTocToggle.addEventListener('click', () => {
      mobileToc.classList.toggle('hidden');
      const isOpen = !mobileToc.classList.contains('hidden');
      mobileTocIcon.style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0deg)';
    });
  }

  // Initialize TOC
  generateTableOfContents();

  // Update active link on scroll
  let ticking = false;
  window.addEventListener('scroll', () => {
    if (!ticking) {
      requestAnimationFrame(() => {
        updateActiveTocLink();
        ticking = false;
      });
      ticking = true;
    }
  });

  /* ===== Tour Tabs Functionality ===== */
  function initTourTabs() {
    const tabButtons = document.querySelectorAll('[data-tab]');
    const tabContents = document.querySelectorAll('[data-tab-content]');

    if (tabButtons.length === 0 || tabContents.length === 0) return;

    function switchTab(targetTab) {
      // Remove active classes from all buttons
      tabButtons.forEach(btn => {
        btn.classList.remove('bg-teal-600', 'text-white');
        btn.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
      });

      // Hide all tab contents
      tabContents.forEach(content => {
        content.classList.add('hidden');
      });

      // Add active class to clicked button
      const activeButton = document.querySelector(`[data-tab="${targetTab}"]`);
      if (activeButton) {
        activeButton.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
        activeButton.classList.add('bg-teal-600', 'text-white');
      }

      // Show target content
      const activeContent = document.querySelector(`[data-tab-content="${targetTab}"]`);
      if (activeContent) {
        activeContent.classList.remove('hidden');
      }
    }

    // Add click event listeners to all tab buttons
    tabButtons.forEach(button => {
      button.addEventListener('click', () => {
        const targetTab = button.getAttribute('data-tab');
        switchTab(targetTab);
      });
    });

    // Initialize first tab as active
    if (tabButtons.length > 0) {
      const firstTab = tabButtons[0].getAttribute('data-tab');
      switchTab(firstTab);
    }
  }

  // Initialize tour tabs
  initTourTabs();

  /* ===== Gallery Modal Functionality ===== */
  function initGalleryModal() {
    const galleryItems = document.querySelectorAll('.gallery-item');
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    const modalAlt = document.getElementById('modalAlt');
    const closeModal = document.getElementById('closeModal');
    const prevImage = document.getElementById('prevImage');
    const nextImage = document.getElementById('nextImage');

    if (!modal || galleryItems.length === 0) return;

    let currentImageIndex = 0;
    let galleryData = [];

    // Collect gallery data
    galleryItems.forEach((item, index) => {
      galleryData.push({
        src: item.dataset.src,
        alt: item.dataset.alt,
        title: item.dataset.title,
        index: index
      });
    });

    function showModal(index) {
      const imageData = galleryData[index];
      if (!imageData) return;

      currentImageIndex = index;
      modalImage.src = imageData.src;
      modalImage.alt = imageData.alt;
      modalTitle.textContent = imageData.title;
      modalAlt.textContent = imageData.alt;

      modal.classList.remove('hidden');
      modal.classList.add('flex');
      document.body.style.overflow = 'hidden';
    }

    function hideModal() {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
      document.body.style.overflow = '';
    }

    function showNextImage() {
      currentImageIndex = (currentImageIndex + 1) % galleryData.length;
      showModal(currentImageIndex);
    }

    function showPrevImage() {
      currentImageIndex = (currentImageIndex - 1 + galleryData.length) % galleryData.length;
      showModal(currentImageIndex);
    }

    // Event listeners
    galleryItems.forEach((item) => {
      item.addEventListener('click', () => {
        const index = parseInt(item.dataset.index);
        showModal(index);
      });
    });

    closeModal.addEventListener('click', hideModal);
    nextImage.addEventListener('click', showNextImage);
    prevImage.addEventListener('click', showPrevImage);

    // Close modal when clicking outside
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        hideModal();
      }
    });

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
      if (!modal.classList.contains('hidden')) {
        switch(e.key) {
          case 'Escape':
            hideModal();
            break;
          case 'ArrowLeft':
            showPrevImage();
            break;
          case 'ArrowRight':
            showNextImage();
            break;
        }
      }
    });
  }

  // Initialize gallery modal
  initGalleryModal();

  /* ===== Legacy Tour Tabs Support (for CSS classes) ===== */
  const legacyTabs = document.querySelectorAll('.tour-tab');
  const legacyContents = document.querySelectorAll('.tour-content');

  if (legacyTabs.length > 0 && legacyContents.length > 0) {
    legacyTabs.forEach(tab => {
      tab.addEventListener('click', () => {
        legacyTabs.forEach(item => {
          item.classList.remove('active-tab');
          item.classList.add('inactive-tab');
        });
        tab.classList.add('active-tab');
        tab.classList.remove('inactive-tab');

        const target = tab.id.replace('tab-', 'content-');
        legacyContents.forEach(content => {
          if (content.id === target) {
            content.classList.remove('hidden');
          } else {
            content.classList.add('hidden');
          }
        });
      });
    });
  }

  /* ===== Competitor Chart Logic ===== */
  const chartCanvas = document.getElementById('competitorChart');

  if (chartCanvas && typeof Chart !== 'undefined') {
    const chartData = {
      labels: ['Price Transparency', 'Comfort & Safety', 'Personalization', 'Guide & Photography'],
      datasets: [{
        label: 'Penidatour',
        data: [9.5, 9.8, 9.0, 9.2],
        backgroundColor: 'rgba(0, 128, 128, 0.7)',
        borderColor: 'rgba(0, 128, 128, 1)',
        borderWidth: 1
      }, {
        label: 'Large OTAs (e.g., Klook)',
        data: [7.0, 7.5, 6.0, 7.0],
        backgroundColor: 'rgba(255, 165, 0, 0.7)',
        borderColor: 'rgba(255, 165, 0, 1)',
        borderWidth: 1
      }, {
        label: 'Budget Local Agents',
        data: [5.0, 4.0, 5.0, 3.0],
        backgroundColor: 'rgba(128, 128, 128, 0.7)',
        borderColor: 'rgba(128, 128, 128, 1)',
        borderWidth: 1
      }]
    };

    const comparisonTexts = {
      'Price Transparency': {
        title: 'No Hidden Costs, Ever.',
        text: 'Many budget tours have surprise fees for entrance tickets, photo spots, or even lunch. Large OTAs can be better, but sometimes exclude specific fees. Our "all-inclusive" promise means everything is covered in one clear price, giving you complete peace of mind.'
      },
      'Comfort & Safety': {
        title: 'Your Safety is Our Priority.',
        text: 'We exclusively use modern, well-maintained, air-conditioned cars and experienced drivers who know the challenging local roads. This is a significant step up from the crowded shared vans or the risks of renting a scooter, which are common in budget options.'
      },
      'Personalization': {
        title: 'Your Tour, Your Rules.',
        text: 'Unlike standardized group tours from large OTAs or rigid budget packages, our private tours are fully flexible. You control the pace. Spend more time where you love, skip what you don\'t. It\'s a level of freedom you just can\'t get in a group.'
      },
      'Guide & Photography': {
        title: 'More Than Just a Driver.',
        text: 'Our drivers are trained guides who share local insights and, crucially, are skilled photographers. While some OTA drivers are praised for this, it\'s not guaranteed. With us, having a personal photographer to capture your memories is part of the premium service.'
      }
    };

    const ctx = chartCanvas.getContext('2d');
    const competitorChart = new Chart(ctx, {
      type: 'bar',
      data: chartData,
      options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          x: {
            beginAtZero: true,
            max: 10,
            title: {
              display: true,
              text: 'Score (out of 10)',
              font: { size: 14 }
            }
          },
          y: {
            ticks: {
              font: { size: 14 }
            }
          }
        },
        plugins: {
          legend: {
            position: 'top',
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                let label = context.dataset.label || '';
                if (label) {
                  label += ': ';
                }
                if (context.parsed.x !== null) {
                  label += context.parsed.x.toFixed(1);
                }
                return label;
              }
            }
          }
        },
        onClick: (event, elements) => {
          if (elements.length > 0) {
            const index = elements[0].index;
            const label = chartData.labels[index];
            updateComparisonText(label);
          }
        }
      }
    });

    const comparisonTextElement = document.getElementById('comparison-text');
    function updateComparisonText(label) {
      const content = comparisonTexts[label];
      if (content) {
        comparisonTextElement.innerHTML = `
          <h4 class="font-bold text-lg text-gray-800">${content.title}</h4>
          <p class="text-gray-600">${content.text}</p>
        `;
      }
    }

    // Initialize with first comparison text
    if (comparisonTextElement) {
      updateComparisonText('Price Transparency');
    }
  }
});

class BusinessDirectory {
    constructor() {
        this.currentPage = 1;
        this.isLoading = false;
        this.hasMore = true;
        this.filters = {
            search: '',
            category: '',
            sort: 'title-asc'
        };
        
        this.elements = {};
        this.init();
    }

    init() {
        // Only initialize if we're on business directory page
        if (!document.querySelector('.business-directory-page')) return;
        
        this.cacheElements();
        this.bindEvents();
        this.loadBusinesses(true);
    }

    cacheElements() {
        this.elements = {
            businessSearch: document.getElementById('business-search'),
            categoryFilter: document.getElementById('category-filter'),
            sortBy: document.getElementById('sort-by'),
            resetFilters: document.getElementById('reset-filters'),
            loadMore: document.getElementById('load-more'),
            resultsCount: document.getElementById('results-count'),
            loadingIndicator: document.getElementById('loading-indicator'),
            businessListings: document.getElementById('business-listings'),
            noResults: document.getElementById('no-results'),
            loadMoreContainer: document.getElementById('load-more-container')
        };
    }

    bindEvents() {
        // Search input with debounce
        this.elements.businessSearch.addEventListener('input', this.debounce(() => {
            this.filters.search = this.elements.businessSearch.value;
            this.resetAndLoad();
        }, 500));

        // Category filter
        this.elements.categoryFilter.addEventListener('change', () => {
            this.filters.category = this.elements.categoryFilter.value;
            this.resetAndLoad();
        });

        // Sort filter
        this.elements.sortBy.addEventListener('change', () => {
            this.filters.sort = this.elements.sortBy.value;
            this.resetAndLoad();
        });

        // Reset filters
        this.elements.resetFilters.addEventListener('click', () => {
            this.resetFilters();
        });

        // Load more - DIHAPUS karena pakai pagination
        // if (this.elements.loadMore) {
        //     this.elements.loadMore.addEventListener('click', () => {
        //         this.loadMore();
        //     });
        // }

        // Pagination click handlers - DITAMBAHKAN
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('pagination-number') || 
                e.target.classList.contains('pagination-btn') ||
                e.target.closest('.pagination-number') ||
                e.target.closest('.pagination-btn')) {
                
                const btn = e.target.classList.contains('pagination-number') || 
                           e.target.classList.contains('pagination-btn') 
                           ? e.target 
                           : e.target.closest('.pagination-number, .pagination-btn');
                
                if (btn && !btn.disabled && !btn.classList.contains('active')) {
                    const page = parseInt(btn.dataset.page);
                    if (page && page > 0) {
                        this.currentPage = page;
                        this.loadBusinesses(true);
                        
                        // Scroll to top of listings
                        const listingsTop = this.elements.businessListings.getBoundingClientRect().top + window.pageYOffset - 100;
                        window.scrollTo({
                            top: listingsTop,
                            behavior: 'smooth'
                        });
                    }
                }
            }
        });

        // Infinite scroll - DIHAPUS karena pakai pagination
        // window.addEventListener('scroll', this.debounce(() => {
        //     this.checkInfiniteScroll();
        // }, 100));
    }

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    resetFilters() {
        this.elements.businessSearch.value = '';
        this.elements.categoryFilter.value = '';
        this.elements.sortBy.value = 'title-asc';
        
        this.filters = {
            search: '',
            category: '',
            sort: 'title-asc'
        };
        
        this.resetAndLoad();
    }

    resetAndLoad() {
        this.currentPage = 1;
        this.hasMore = true;
        this.loadBusinesses(true);
    }

    async loadBusinesses(replaceContent = false) {
        if (this.isLoading) return;
        
        this.isLoading = true;
        
        if (replaceContent) {
            this.showLoading();
        }

        try {
            const formData = new FormData();
            formData.append('action', 'get_business_listings_template');
            formData.append('page', this.currentPage);
            formData.append('filters', JSON.stringify(this.filters));
            formData.append('nonce', window.businessDirectory.nonce);

            const response = await fetch(window.businessDirectory.ajax_url, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success && data.data) {
                this.handleSuccess(data.data, replaceContent);
            } else {
                this.handleError(data.data || 'Failed to load businesses');
            }
        } catch (error) {
            console.error('Business Directory Error:', error);
            this.handleError('An error occurred while loading data.');
        } finally {
            this.isLoading = false;
            this.hideLoading();
        }
    }

    handleSuccess(data, replaceContent) {
        // PERBAIKAN UTAMA: Cek apakah ada HTML yang valid
        const hasValidHtml = data.html && data.html.trim() !== '';
        
        if (hasValidHtml) {
            // Ada data, tampilkan hasil
            if (replaceContent) {
                this.elements.businessListings.innerHTML = data.html;
            } else {
                this.elements.businessListings.insertAdjacentHTML('beforeend', data.html);
            }
            
            // Update info
            this.updateResultsCount(data.total, data.start, data.end);
            
            // Handle pagination
            this.handlePagination(data.pagination);
            
            // Hide no results
            this.elements.noResults.style.display = 'none';
            this.elements.businessListings.style.display = 'flex';
            
        } else {
            // Tidak ada data, tampilkan no results
            this.showNoResults();
        }
    }

    handlePagination(paginationHtml) {
        // Remove existing pagination
        const existingPagination = document.querySelector('.business-pagination');
        if (existingPagination) {
            existingPagination.remove();
        }
        
        // Add new pagination if available
        if (paginationHtml && paginationHtml.trim() !== '') {
            this.elements.businessListings.insertAdjacentHTML('afterend', paginationHtml);
        }
    }

    showNoResults() {
        this.elements.businessListings.innerHTML = '';
        this.elements.businessListings.style.display = 'none';
        this.elements.noResults.style.display = 'block';
        this.elements.resultsCount.textContent = '0 businesses found';
        
        // Remove pagination
        const existingPagination = document.querySelector('.business-pagination');
        if (existingPagination) {
            existingPagination.remove();
        }
    }

    handleError(message) {
        this.elements.businessListings.innerHTML = `
            <div class="error-message">
                <h3>Error Occurred</h3>
                <p>${message}</p>
                <button onclick="window.businessDirectoryInstance.loadBusinesses(true)" class="retry-btn">Try Again</button>
            </div>
        `;
        this.elements.noResults.style.display = 'none';
    }

    async loadMore() {
        if (!this.hasMore || this.isLoading) return;
        
        this.currentPage++;
        await this.loadBusinesses(false);
    }

    checkInfiniteScroll() {
        if (!this.hasMore || this.isLoading) return;

        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        const threshold = 500;

        if (scrollTop + windowHeight >= documentHeight - threshold) {
            this.loadMore();
        }
    }

    showLoading() {
        this.elements.loadingIndicator.style.display = 'block';
        this.elements.businessListings.style.display = 'none';
        this.elements.noResults.style.display = 'none';
    }

    hideLoading() {
        this.elements.loadingIndicator.style.display = 'none';
    }

    updateResultsCount(total, start, end) {
        let text = '';
        if (total > 0) {
            text = `Showing ${start}-${end} of ${total} businesses`;
        } else {
            text = '0 businesses found';
        }
        this.elements.resultsCount.textContent = text;
    }

    toggleLoadMore() {
        // TIDAK DIGUNAKAN LAGI karena pakai pagination
        if (this.elements.loadMoreContainer) {
            this.elements.loadMoreContainer.style.display = 'none';
        }
    }

    toggleNoResults() {
        // DIPERBAIKI: Cek berdasarkan class yang benar
        const hasResults = this.elements.businessListings.querySelectorAll('.business-card-google').length > 0;
        
        if (hasResults) {
            this.elements.noResults.style.display = 'none';
            this.elements.businessListings.style.display = 'flex';
        } else {
            this.elements.noResults.style.display = 'block';
            this.elements.businessListings.style.display = 'none';
        }
    }
}

// Utility function to sanitize HTML (for security)
const sanitizeHTML = (str) => {
    const temp = document.createElement('div');
    temp.textContent = str;
    return temp.innerHTML;
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.businessDirectoryInstance = new BusinessDirectory();
});

// Add some utility functions for better UX
document.addEventListener('DOMContentLoaded', () => {
    // Add loading state to buttons
    const addLoadingState = (button) => {
        if (button) {
            button.addEventListener('click', function(e) {
                if (this.disabled) {
                    e.preventDefault();
                    return;
                }
                
                const originalText = this.textContent;
                this.disabled = true;
                this.textContent = 'Loading...';
                
                // Revert after 5 seconds if something goes wrong
                setTimeout(() => {
                    if (this.disabled) {
                        this.disabled = false;
                        this.textContent = originalText;
                    }
                }, 5000);
            });
        }
    };

    // Add loading state to reset button
    const resetBtn = document.getElementById('reset-filters');
    addLoadingState(resetBtn);

    // Add keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        // Ctrl/Cmd + F to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            const searchInput = document.getElementById('business-search');
            if (searchInput) {
                searchInput.focus();
            }
        }
        
        // Escape to clear search
        if (e.key === 'Escape') {
            const searchInput = document.getElementById('business-search');
            if (document.activeElement === searchInput && searchInput.value) {
                searchInput.value = '';
                if (window.businessDirectoryInstance) {
                    window.businessDirectoryInstance.filters.search = '';
                    window.businessDirectoryInstance.resetAndLoad();
                }
            }
        }
    });

    // Add intersection observer for lazy loading images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                }
            });
        });

        // Observe images on load and after AJAX
        const observeImages = () => {
            const lazyImages = document.querySelectorAll('img.lazy[data-src]');
            lazyImages.forEach(img => imageObserver.observe(img));
        };
        
        observeImages();
        
        // Re-observe after AJAX content loads
        const observer = new MutationObserver(() => {
            observeImages();
        });
        
        const businessListings = document.getElementById('business-listings');
        if (businessListings) {
            observer.observe(businessListings, {
                childList: true,
                subtree: true
            });
        }
    }
});

// Error handling for failed AJAX requests
window.addEventListener('unhandledrejection', (event) => {
    console.error('Unhandled promise rejection:', event.reason);
});

// Export for potential module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = BusinessDirectory;
}
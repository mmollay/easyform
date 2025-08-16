/**
 * EasyForm Documentation - Main JavaScript
 */

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    initializeNavigation();
    initializeCodeHighlighting();
    initializeScrollSpy();
    initializeCopyButtons();
    initializeInteractiveDemos();
    initializeLazyLoading();
});

/**
 * Navigation functionality
 */
function initializeNavigation() {
    const mobileToggle = document.querySelector('.mobile-menu-toggle');
    const nav = document.querySelector('.nav');
    
    if (mobileToggle && nav) {
        mobileToggle.addEventListener('click', function() {
            nav.classList.toggle('active');
        });
        
        // Close mobile nav when clicking outside
        document.addEventListener('click', function(e) {
            if (!nav.contains(e.target) && !mobileToggle.contains(e.target)) {
                nav.classList.remove('active');
            }
        });
    }
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const headerHeight = document.querySelector('.header')?.offsetHeight || 0;
                const targetPosition = target.offsetTop - headerHeight - 20;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

/**
 * Code syntax highlighting using Prism.js
 */
function initializeCodeHighlighting() {
    // Load Prism.js if not already loaded
    if (typeof Prism === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js';
        script.onload = function() {
            // Load PHP and JavaScript components
            loadPrismComponent('prism-php');
            loadPrismComponent('prism-javascript');
            loadPrismComponent('prism-json');
            loadPrismComponent('prism-bash');
            loadPrismComponent('prism-css');
        };
        document.head.appendChild(script);
        
        // Load Prism CSS
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css';
        document.head.appendChild(link);
    }
    
    // Auto-detect and highlight code blocks
    document.querySelectorAll('pre code').forEach(block => {
        if (!block.classList.contains('language-')) {
            // Try to detect language from parent data attribute or content
            const language = detectLanguage(block.textContent);
            block.classList.add(`language-${language}`);
        }
    });
}

/**
 * Load Prism component dynamically
 */
function loadPrismComponent(component) {
    const script = document.createElement('script');
    script.src = `https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/${component}.min.js`;
    script.onload = function() {
        if (typeof Prism !== 'undefined') {
            Prism.highlightAll();
        }
    };
    document.head.appendChild(script);
}

/**
 * Detect programming language from code content
 */
function detectLanguage(code) {
    if (code.includes('<?php')) return 'php';
    if (code.includes('function') && code.includes('{')) return 'javascript';
    if (code.includes('"') && code.includes(':') && code.includes('{')) return 'json';
    if (code.includes('$') && code.includes('->')) return 'php';
    if (code.includes('npm') || code.includes('composer')) return 'bash';
    if (code.includes('background:') || code.includes('color:')) return 'css';
    return 'markup';
}

/**
 * Scroll spy for documentation navigation
 */
function initializeScrollSpy() {
    const docNav = document.querySelector('.doc-nav');
    if (!docNav) return;
    
    const navLinks = docNav.querySelectorAll('a[href^="#"]');
    const sections = Array.from(navLinks).map(link => 
        document.querySelector(link.getAttribute('href'))
    ).filter(Boolean);
    
    if (sections.length === 0) return;
    
    function updateActiveNav() {
        const scrollPos = window.scrollY + 100;
        
        let activeSection = null;
        sections.forEach(section => {
            if (section.offsetTop <= scrollPos) {
                activeSection = section;
            }
        });
        
        navLinks.forEach(link => link.classList.remove('active'));
        
        if (activeSection) {
            const activeLink = docNav.querySelector(`a[href="#${activeSection.id}"]`);
            if (activeLink) {
                activeLink.classList.add('active');
            }
        }
    }
    
    window.addEventListener('scroll', throttle(updateActiveNav, 100));
    updateActiveNav(); // Initial call
}

/**
 * Add copy buttons to code blocks
 */
function initializeCopyButtons() {
    document.querySelectorAll('.code-block, pre').forEach(codeBlock => {
        const button = document.createElement('button');
        button.className = 'copy-btn';
        button.innerHTML = `
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16 1H4C2.9 1 2 1.9 2 3V17H4V3H16V1ZM19 5H8C6.9 5 6 5.9 6 7V21C6 22.1 6.9 23 8 23H19C20.1 23 21 22.1 21 21V7C21 5.9 20.1 5 19 5ZM19 21H8V7H19V21Z" fill="currentColor"/>
            </svg>
            Copy
        `;
        
        button.style.cssText = `
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 4px;
            opacity: 0;
            transition: opacity 0.3s ease;
        `;
        
        codeBlock.style.position = 'relative';
        codeBlock.appendChild(button);
        
        // Show button on hover
        codeBlock.addEventListener('mouseenter', () => {
            button.style.opacity = '1';
        });
        
        codeBlock.addEventListener('mouseleave', () => {
            button.style.opacity = '0';
        });
        
        // Copy functionality
        button.addEventListener('click', async () => {
            const code = codeBlock.querySelector('code')?.textContent || codeBlock.textContent;
            
            try {
                await navigator.clipboard.writeText(code);
                button.innerHTML = `
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 16.17L4.83 12L3.41 13.41L9 19L21 7L19.59 5.59L9 16.17Z" fill="currentColor"/>
                    </svg>
                    Copied!
                `;
                
                setTimeout(() => {
                    button.innerHTML = `
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 1H4C2.9 1 2 1.9 2 3V17H4V3H16V1ZM19 5H8C6.9 5 6 5.9 6 7V21C6 22.1 6.9 23 8 23H19C20.1 23 21 22.1 21 21V7C21 5.9 20.1 5 19 5ZM19 21H8V7H19V21Z" fill="currentColor"/>
                        </svg>
                        Copy
                    `;
                }, 2000);
            } catch (err) {
                console.error('Failed to copy code:', err);
            }
        });
    });
}

/**
 * Initialize interactive demos
 */
function initializeInteractiveDemos() {
    // Form preview functionality
    document.querySelectorAll('.demo-form').forEach(demo => {
        const codeElement = demo.querySelector('.demo-code');
        const previewElement = demo.querySelector('.demo-preview');
        
        if (codeElement && previewElement) {
            // Create live preview button
            const previewBtn = document.createElement('button');
            previewBtn.className = 'btn btn-outline';
            previewBtn.textContent = 'Live Preview';
            previewBtn.onclick = () => generateFormPreview(codeElement.textContent, previewElement);
            
            demo.appendChild(previewBtn);
        }
    });
    
    // Tabbed content
    document.querySelectorAll('.tabs').forEach(tabContainer => {
        const tabs = tabContainer.querySelectorAll('.tab-btn');
        const panels = tabContainer.querySelectorAll('.tab-panel');
        
        tabs.forEach((tab, index) => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs and panels
                tabs.forEach(t => t.classList.remove('active'));
                panels.forEach(p => p.classList.remove('active'));
                
                // Add active class to clicked tab and corresponding panel
                tab.classList.add('active');
                if (panels[index]) {
                    panels[index].classList.add('active');
                }
            });
        });
    });
}

/**
 * Generate form preview from PHP code
 */
function generateFormPreview(phpCode, previewElement) {
    // This would typically make an AJAX request to a PHP endpoint
    // For now, show a placeholder
    previewElement.innerHTML = `
        <div class="alert alert-info">
            <strong>Live Preview:</strong> This would render the actual form based on the PHP code.
            <br><small>Implementation requires server-side PHP execution.</small>
        </div>
        <div class="form-preview-placeholder">
            <div class="form-field">
                <label>Sample Field</label>
                <input type="text" placeholder="This is a preview..." class="form-control" disabled>
            </div>
            <button type="button" class="btn btn-primary" disabled>Submit</button>
        </div>
    `;
}

/**
 * Lazy loading for images and heavy content
 */
function initializeLazyLoading() {
    if ('IntersectionObserver' in window) {
        const lazyImages = document.querySelectorAll('img[data-src]');
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        lazyImages.forEach(img => imageObserver.observe(img));
    }
}

/**
 * Utility function to throttle function calls
 */
function throttle(func, wait) {
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

/**
 * API for external scripts to interact with documentation
 */
window.EasyFormDocs = {
    highlightCode: function() {
        if (typeof Prism !== 'undefined') {
            Prism.highlightAll();
        }
    },
    
    showAlert: function(message, type = 'info') {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type}`;
        alert.textContent = message;
        alert.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 300px;
            animation: slideInRight 0.3s ease;
        `;
        
        document.body.appendChild(alert);
        
        setTimeout(() => {
            alert.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    },
    
    scrollToSection: function(sectionId) {
        const section = document.getElementById(sectionId);
        if (section) {
            const headerHeight = document.querySelector('.header')?.offsetHeight || 0;
            const targetPosition = section.offsetTop - headerHeight - 20;
            
            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
        }
    }
};

/**
 * Add custom CSS animations
 */
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    
    .form-preview-placeholder {
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
        margin-top: 10px;
    }
    
    .form-field {
        margin-bottom: 15px;
    }
    
    .form-field label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
    }
    
    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }
    
    .tabs {
        margin: 20px 0;
    }
    
    .tab-buttons {
        display: flex;
        border-bottom: 1px solid #ddd;
        margin-bottom: 20px;
    }
    
    .tab-btn {
        background: none;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        transition: all 0.3s ease;
    }
    
    .tab-btn.active {
        border-bottom-color: var(--primary-color);
        color: var(--primary-color);
    }
    
    .tab-panel {
        display: none;
    }
    
    .tab-panel.active {
        display: block;
    }
`;
document.head.appendChild(style);
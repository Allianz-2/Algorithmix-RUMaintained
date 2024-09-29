const progress = document.getElementById('progress');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const steps = document.querySelectorAll('.step');

let currentStep = 1;

nextBtn.addEventListener('click', () => {
    if (currentStep < steps.length) {
        currentStep++;
        updateProgress();
    }
});

prevBtn.addEventListener('click', () => {
    if (currentStep > 1) {
        currentStep--;
        updateProgress();
    }
});

function updateProgress() {
    steps.forEach((step, index) => {
        if (index < currentStep) {
            step.classList.add('active');
        } else {
            step.classList.remove('active');
        }
    });

    const progressWidth = ((currentStep - 1) / (steps.length - 1)) * 100 + '%';
    progress.style.width = progressWidth;

    prevBtn.disabled = currentStep === 1;
    nextBtn.disabled = currentStep === steps.length;
}

document.getElementById('menu-btn').addEventListener('click', function() {
    const popupMenu = document.getElementById('popup-menu');
    popupMenu.style.display = popupMenu.style.display === 'none' || popupMenu.style.display === '' ? 'block' : 'none';
});

function showSection(sectionId) {
    // Hide all sections
    document.querySelectorAll('.popup-menu-content').forEach(el => el.classList.remove('active'));
    // Show the selected section
    document.getElementById(sectionId).classList.add('active');
}

// JavaScript to trigger file input click when the custom upload area is clicked
const uploadArea = document.getElementById('upload-area');
const fileInput = document.getElementById('photo');

uploadArea.addEventListener('click', function() {
    fileInput.click();
});

// Optional: Show the selected file name
fileInput.addEventListener('change', function() {
    if (fileInput.files.length > 0) {
        uploadArea.querySelector('p').textContent = fileInput.files[0].name;
    }
});
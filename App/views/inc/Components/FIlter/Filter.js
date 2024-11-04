// Component/FilterComponent/filterComponent.js
document.addEventListener('DOMContentLoaded', function() {
    const costRange = document.getElementById('costRange');
    const sliderValue = document.getElementById('sliderValue');

    costRange.addEventListener('input', function() {
        sliderValue.textContent = `Rs. ${this.value}`;
        const percent = ((this.value - this.min) / (this.max - this.min)) * 100;
        sliderValue.style.left = `calc(${percent}% - ${sliderValue.offsetWidth / 2}px)`;
    });
});

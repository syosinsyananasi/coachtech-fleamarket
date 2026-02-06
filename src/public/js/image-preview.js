document.addEventListener('DOMContentLoaded', function() {
    var imageInputs = document.querySelectorAll('[data-image-preview]');

    imageInputs.forEach(function(input) {
        var previewSelector = input.getAttribute('data-image-preview');
        var preview = document.querySelector(previewSelector);
        var altText = input.getAttribute('data-preview-alt') || '';

        if (preview) {
            input.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        if (altText) {
                            preview.innerHTML = '<img src="' + e.target.result + '" alt="' + altText + '">';
                        } else {
                            preview.innerHTML = '<img src="' + e.target.result + '">';
                        }
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        }
    });
});

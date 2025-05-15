function toggleAvailability(imageId, currentStatus) {
    const newStatus = currentStatus === 'available' ? 'unavailable' : 'available';
    window.location.href = 'auth/availability.php?id=' + imageId + '&status=' + newStatus;
}

function deleteImage(imagePath) {
    if (confirm('Are you sure you want to delete this image?')) {
        window.location.href = 'delete_image.php?image=' + encodeURIComponent(imagePath);
    }
}
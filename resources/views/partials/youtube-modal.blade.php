<!-- Modal para reproducir videos -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe id="youtubeIframe" src="" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const videoModal = document.getElementById('videoModal');
        
        videoModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const videoId = button.getAttribute('data-video-id');
            const iframe = document.getElementById('youtubeIframe');
            
            iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&modestbranding=1`;
        });

        videoModal.addEventListener('hide.bs.modal', function() {
            const iframe = document.getElementById('youtubeIframe');
            iframe.src = '';
        });
    });
</script>

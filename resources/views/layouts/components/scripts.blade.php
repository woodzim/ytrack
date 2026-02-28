
         <!-- Scroll To Top -->
         <div class="scrollToTop">
            <span class="arrow lh-1"><i class="ti ti-arrow-big-up fs-18"></i></span>
         </div>
         <div id="responsive-overlay"></div>
         <!-- Scroll To Top -->

         <!-- Popper JS -->
         <script src="{{asset('build/assets/libs/@popperjs/core/umd/popper.min.js')}}"></script>

         <!-- Bootstrap JS -->
         <script src="{{asset('build/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

         <!-- Node Waves JS-->
         <script src="{{asset('build/assets/libs/node-waves/waves.min.js')}}"></script>

         <!-- Simplebar JS -->
         <script src="{{asset('build/assets/libs/simplebar/simplebar.min.js')}}"></script>
         @vite('resources/assets/js/simplebar.js')

         <!-- Auto Complete JS -->
         <script src="{{asset('build/assets/libs/@tarekraafat/autocomplete.js/autoComplete.min.js')}}"></script>

         <!-- Color Picker JS -->
         <script src="{{asset('build/assets/libs/@simonwep/pickr/pickr.es5.min.js')}}"></script>

         <!-- Date & Time Picker JS -->
         <script src="{{asset('build/assets/libs/flatpickr/flatpickr.min.js')}}"></script>

         <!-- Choices JS -->
         <script src="{{asset('build/assets/libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>

         <!-- GLightbox JS -->
         <script src="{{asset('build/assets/libs/glightbox/js/glightbox.min.js')}}"></script>

         <script>
            window.choicesInstances = new Map();

            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Choices for all selects except those with .no-choices or .select-choices (manual init)
                const genericSelects = document.querySelectorAll('select:not(.no-choices):not(.select-choices)');
                genericSelects.forEach(element => {
                    if (!window.choicesInstances.has(element)) {
                        const instance = new Choices(element, {
                            allowHTML: true,
                            placeholder: true,
                            placeholderValue: 'Pilih opsi...',
                            searchPlaceholderValue: 'Cari...',
                            itemSelectText: '',
                            noResultsText: 'Tidak ada hasil ditemukan',
                            noChoicesText: 'Tidak ada pilihan tersedia',
                            searchEnabled: element.options.length > 10,
                        });
                        window.choicesInstances.set(element, instance);
                    }
                });
            });

            // Helper to update Choices UI when underlying select value is changed programmatically
            function updateChoices(element) {
                if (window.choicesInstances.has(element)) {
                    const instance = window.choicesInstances.get(element);
                    instance.setChoiceByValue(element.value);
                }
            }
         </script>
         
         @yield('scripts')

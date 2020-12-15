        var resetButton = document.getElementById("search");
        var timer;
        //
        //        response.setHeader("Set-Cookie", "HttpOnly;Secure;SameSite=Strict");

        function reset() {
            if (resetButton) {
                resetButton.value = "";
            }
            document.getElementById("clear-img").style.display = "none";
        }
        //        document.getElementById("clear-img").style.display = "none";

        // Bind keyup event on the input
        $('#search').keyup(function () {

            // If value is not empty
            if ($(this).val().length == 0) {
                // Hide the element
                $('#clear-img').hide();
            } else {
                // Otherwise show it
                $('#clear-img').show();
            }
        }).keyup();


        // Get the modal
        var modal = document.getElementById("myModal");

        // Get the button that opens the modal
        var btn = document.getElementById("mic");

        // When the user clicks the button, open the modal 
        btn.onclick = function () {
            modal.style.display = "flex";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        function startDictation() {

            if (window.hasOwnProperty('webkitSpeechRecognition')) {

                var recognition = new webkitSpeechRecognition();

                recognition.continuous = false;
                recognition.interimResults = false;
                recognition.lang = "en-US";
                recognition.start();

                recognition.onresult = function (e) {
                    document.getElementById('search').value = e.results[0][0].transcript;
                    recognition.stop();
                    document.getElementById('mainSearch').submit();
                };
                recognition.onerror = function (e) {
                    recognition.stop();
                }
            }

            setTimeout(function () {
                modal.style.display = "none";
            }, 13500);

        }

        function submit() {
            document.getElementById('mainSearch').submit();
        }

        function ipLookUp() {
            $.ajax('http://ip-api.com/json')
                .then(
                    function success(response) {
                        console.log('User\'s Location Data is ', response);
                        console.log('User\'s City', response.city);
                        document.getElementById("userCountry").innerHTML = response.country;
                        document.getElementById("userCity").innerHTML = response.city;
                        document.getElementById("userRegion").innerHTML = response.zip;
                    },

                    function fail(data, status) {
                        console.log('Request failed.  Returned status of',
                            status);
                    }
                );
        }
        ipLookUp();

        $(document).ready(function () {


            $(".result").on("click", function () {

                var id = $(this).attr("data-linkId");
                var url = $(this).attr("href");

                if (!id) {
                    alert("data-linkId attribute not found");
                }

                increaseLinkClicks(id, url);

                return false;
            });


            var grid = $(".imageResults");

            grid.on("layoutComplete", function () {
                $(".gridItem img").css("visibility", "visible");
            });

            grid.masonry({
                itemSelector: ".gridItem",
                columnWidth: 200,
                gutter: 5,
                isInitLayout: false
            });

            $("[data-fancybox]").fancybox();

        });


        function loadImage(src, className) {

            var image = $("<img>");

            image.on("load", function () {
                $("." + className + " a").append(image);
                clearTimeout(timer);
                timer = setTimeout(function () {
                    $(".imageResults").masonry();
                }, 300);

            });

            image.on("error", function () {

                $("." + className).remove();

                $.post("ajax/setBroken.php", {
                    src: src
                });

            });

            image.attr("src", src);

        }


        function increaseLinkClicks(linkId, url) {

            $.post("ajax/updateLinkCount.php", {
                    linkId: linkId
                })
                .done(function (result) {
                    if (result != "") {
                        alert(result);
                        return;
                    }

                    window.location.href = url;
                });

        }

        function increaseImageClicks(imageUrl) {

            $.post("ajax/updateImageCount.php", {
                    imageUrl: imageUrl
                })
                .done(function (result) {
                    if (result != "") {
                        alert(result);
                        return;
                    }
                });

        }

/**
 * 	ID Facebook Profiles - Javascript Core
 * 	--------------------------------------------
 * 	vers. 0.1.1b - June 2014 - rev. 0.1.35
 * 	Developed by Gianluigi 'A35G'
 * 	--------------------------------------------
 * 	https://github.com/A35G/Facebook-ID-Profiles
 * 	--------------------------------------------
 * 	http://www.gmcode.it - www.hackworld.it
 */

$(window).load(function() {

	//	Empty Control Function
	function empty(mixed_var) {
		return (mixed_var === "" || mixed_var === 0 || mixed_var === "0" || mixed_var === null || mixed_var === false || mixed_var === undefined || mixed_var.length === 0);
	}

	//	If variable is a number
	function isNum(num) {
		return (!isNaN(num) && !isNaN(parseFloat(num)));
	}

	String.prototype.trim = function() {
		return this.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
	}

	//	Not cache for jQuery
	jQuery.ajaxSetup({
		cache: false
	});

	/**
	 * Function to validate URL in this first step
	 * @param {String} url_form: URL to analyze and validate
	 */
	function validURL(url_form) {

	  var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
	    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
	    '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
	    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
	    '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
	    '(\\#[-a-z\\d_]*)?$','i'); // fragment locater

	  if(!pattern.test(url_form)) {

	    alert("Please enter a valid URL.");
	    return false;

	  } else {

	    return true;

	  }

	}

	$(function(){

		//	Reset value of field in the form
		$('#url_photo').val('');

		//	Reset content of the div for result of search
		$('#resp_search').empty();

		//	Dynamic attribute to open URL in a new page
		$('a[rel="external"]').attr('target', '_blank');

		//	Bind the click at tag 'A', content into the form
		$('body').on('click', 'a#analyze_field', function(e) {

			e.preventDefault();

			var photo_fb = $('#url_photo').val();
			photo_fb = photo_fb.trim();

			$('#loading-data').css('display', 'block');

			//	Check if variable is fill
			if (!empty(photo_fb)) {

				//	Check if content of variable is a valid url
				if (validURL(photo_fb)) {

					//	AJAX request to search information by url of image inserted
					$.ajax({
						type: "POST",
						url: "./pages/search.php",
						data: { url_p: photo_fb },
						cache: false,
						success: function(res) {

							$('#loading-data').css('display', 'none');

							//	Show results of search
							if (!empty(res))
								$('#resp_search').empty().html(res);
							else
								alert('An error has occurred');

						}
					});

				}

			} else {

		    alert("The field can't be empty!!!\r\n\r\nPlease enter a valid URL.");
		    return false;

			}

		});

	})

})
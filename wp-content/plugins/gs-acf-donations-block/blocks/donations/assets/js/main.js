/*
 * Donations block script
 */
(function ($) {

	// Helper function to initialize donations.
	initializeBlock = function () {

		function easeOutCubic(x) {
			return 1 - Math.pow(1 - x, 3);
		}

		function animateProgressBar(element, start, end, duration) {
			var startTimestamp = null;
			var step = function (timestamp) {
				if (!startTimestamp) startTimestamp = timestamp;
				var progress = Math.min((timestamp - startTimestamp) / duration, 1);
				var easedProgress = easeOutCubic(progress);
				var currentProgress = start + (end - start) * easedProgress;
				element.style.width = currentProgress + '%';
				if (progress < 1) {
					window.requestAnimationFrame(step);
				}
			};
			window.requestAnimationFrame(step);
		}

		function animateValue(element, start, end, duration) {
			var startTimestamp = null;
			var numberFormatter = new Intl.NumberFormat('en-US', {
				style: 'currency',
				currency: 'USD',
				maximumFractionDigits: 0 // Removes any decimal places
			});

			var step = function (timestamp) {
				if (!startTimestamp) startTimestamp = timestamp;
				var progress = Math.min((timestamp - startTimestamp) / duration, 1);
				var easedProgress = easeOutCubic(progress);
				var currentNumber = Math.floor(start + (end - start) * easedProgress);
				element.innerText = numberFormatter.format(currentNumber);
				if (progress < 1) {
					window.requestAnimationFrame(step);
				}
			};
			window.requestAnimationFrame(step);
		}

		var currentAmount = parseFloat(document.getElementById('current-amount').getAttribute('data-amount'));
		var targetAmount = parseFloat(document.getElementById('target-amount').getAttribute('data-amount'));
		var progressPercentage = (currentAmount / targetAmount) * 100;

		animateValue(document.getElementById('current-donation'), 0, currentAmount, 2000); // Animate the current amount
		animateProgressBar(document.querySelector('.progress-bar'), 0, progressPercentage, 2000); // Animate the progress bar

		// Insert donation description in the ACF form
		document.addEventListener('DOMContentLoaded', function () {
			// Create the new element
			var descriptionDiv = document.createElement('div');
			descriptionDiv.className = 'donation-description';
			descriptionDiv.innerHTML = document.getElementById('donation-description-sr').innerHTML;

			var descriptionDivAmount = document.createElement('div');
			descriptionDivAmount.className = 'donation-total-amount';

			// Find the target element where you want to insert the new element
			var targetDiv = document.querySelector('.acf-field-number');
			var targetDivSubmit = document.querySelector('.acf-form-submit');

			// Insert the new element into the target element
			if (targetDiv) {
				targetDiv.appendChild(descriptionDiv);
			}



			// total donation amount

			// Access the input element
			var inputElement = document.getElementById('acf-field_6580889e7a473');

			// Access the output div
			var outputDiv = targetDivSubmit;

			// Function to append text to the output div
			function appendToOutput(text) {
				// var p = document.createElement('p');
				descriptionDivAmount.innerHTML = text;
				outputDiv.prepend(descriptionDivAmount);
			}

			// Output the default value
			appendToOutput('<span class="colored-label">Donation Total: </span><span> $' + inputElement.value + '</span>');

			// Event listener for changes in the input field
			inputElement.addEventListener('input', function () {
				appendToOutput('<span class="colored-label">Donation Total: </span><span> $' + inputElement.value + '</span>');
			});


		});

	}

	initializeBlock(); // Initialize donation block

})(jQuery);
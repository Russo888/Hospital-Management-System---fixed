'use strict';
var UIButtons = function () {

	var laddaHandler = function () {
		// Bind normal buttons
		Ladda.bind('div:not(.progress-demo) .ladda-button', {
			timeout: 2000
		});

		// Bind progress buttons and simulate loading progress
		Ladda.bind('.progress-demo .ladda-button', {
			callback: function (instance) {
				var progress = 0;
				var interval = setInterval(function () {
					// Righe modificate per correggere la vulnerabilità:
					// Generazione di un numero casuale sicuro tramite l'API Web Crypto
					var cryptoArray = new Uint32Array(1);
					window.crypto.getRandomValues(cryptoArray);
					// Divide il valore per il massimo possibile di un Uint32 (0xFFFFFFFF + 1) per ottenere un float tra 0 e 1
					var secureRandom = cryptoArray[0] / 4294967296;
					progress = Math.min(progress + secureRandom * 0.1, 1);
					instance.setProgress(progress);

					if (progress === 1) {
						instance.stop();
						clearInterval(interval);
					}
				}, 200);
			}
		});

		// You can control loading explicitly using the JavaScript API
		// as outlined below:

		// var l = Ladda.create( document.querySelector( 'button' ) );
		// l.start();
		// l.stop();
		// l.toggle();
		// l.isLoading();
		// l.setProgress( 0-1 );
	};
	return {
		init: function () {
			laddaHandler();
		}
	};
}();

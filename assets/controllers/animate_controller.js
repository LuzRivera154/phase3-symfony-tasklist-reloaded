import { Controller } from '@hotwired/stimulus';
import autoAnimate from '@formkit/auto-animate';

export default class extends Controller {
    connect() {
      
        this.animationManager = autoAnimate(this.element, {
            duration: 350,      
            easing: 'ease-in-out' 
        });

        // La mantenemos apagada para que el checkbox no anime nada
        this.animationManager.disable();
    }

    // Esta función la llamás desde el botón de PIN
    enableForInteraction() {
        this.animationManager.enable();

        // Se apaga sola después de que termina la animación
        setTimeout(() => {
            this.animationManager.disable();
        }, 400);
    }
}
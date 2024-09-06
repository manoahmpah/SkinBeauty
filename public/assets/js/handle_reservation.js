class ReservationHandler {
    constructor(disabledDates = [], disabledTimes = []) {
        this.disabledDates = disabledDates;
        this.disabledTimes = disabledTimes;
    }

    initializeDatepicker() {
        flatpickr("#datepicker", {
            minDate: "today",
            locale: fr,
            disable: [
                (date) => {
                    const dayOfWeek = date.getDay();
                    return dayOfWeek === 0;
                },
                ...this.disabledDates.map(date => new Date(date)),
            ],
            dateFormat: "Y-m-d"
        });
    }

    initializeTimepicker() {
        const timepicker = document.getElementById('timepicker');

        for (let hour = 8; hour <= 17; hour++) {
            for (let minute = 0; minute < 60; minute += 60) {
                let time = ('0' + hour).slice(-2) + ':' + ('0' + minute).slice(-2);
                if (!this.disabledTimes.includes(time)) {
                    const option = document.createElement('option');
                    option.value = time;
                    option.textContent = time;
                    timepicker.appendChild(option);
                }
            }
        }
    }

    removeTimepickerOptions() {
        const timepicker = document.getElementById('timepicker');
        while (timepicker.options.length > 0) {
            timepicker.remove(0);
        }
    }

    toggleTimepicker() {
        const date = document.getElementById('datepicker');
        const time = document.getElementById('timepicker');

        if (date.value === '') {
            time.setAttribute('disabled', 'disabled');
        }

        date.addEventListener('change', () => {
            if (date.value !== '') {
                time.removeAttribute('disabled');
            } else {
                time.setAttribute('disabled', 'disabled');
            }
        });
    }

    getDisabledTime() {
        return this.disabledTimes;
    }

    setDisabledTime(disabledTime) {
        this.disabledTimes = disabledTime;
    }

    resetDisabledTime() {
        this.disabledTimes = [];
    }
}



class Calendar {
    constructor(date = new Date()) {
        this.date = date;
        this.week = [];
        this.heightOfHour = 9;
    }

    getWeekDates(date) {
        const daysOfWeek = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

        let startDate = new Date(date);
        let dayOfWeek = startDate.getDay() === 0 ? 7 : startDate.getDay(); // Corrige pour que Dimanche soit traité comme le dernier jour
        let diff = startDate.getDate() - dayOfWeek + 1; // Ajuste pour que la semaine commence le lundi

        this.week = []; // Initialise la semaine

        for (let i = 0; i < 6; i++) {
            let currentDay = new Date(startDate);
            currentDay.setDate(diff + i);

            let dayIndex = (currentDay.getDay() + 6) % 7;

            this.week.push({
                dayName: daysOfWeek[dayIndex],
                day: currentDay.getDate(),
                month: months[currentDay.getMonth()],
                year: currentDay.getFullYear(),
                dateId: `${currentDay.getFullYear()}-${String(currentDay.getMonth() + 1).padStart(2, '0')}-${String(currentDay.getDate()).padStart(2, '0')}` // Format YYYY-MM-DD
            });
        }
    }

    getNextWeek() {
        let nextWeek = new Date(this.date);
        nextWeek.setDate(nextWeek.getDate() + 7);
        this.date = nextWeek;
        this.getWeekDates(nextWeek);
    }

    getPreviousWeek() {
        let previousWeek = new Date(this.date);
        previousWeek.setDate(previousWeek.getDate() - 7);
        this.date = previousWeek;
        this.getWeekDates(previousWeek);
    }

    createDisplayWeek(daysDiv) {
        this.week.forEach(day => {
            let dayDiv = document.createElement('div');

            let dayName = document.createElement('h1');
            dayName.textContent = day.dayName;

            let dayNumber = document.createElement('span');
            dayNumber.textContent = day.day;

            dayDiv.appendChild(dayName);
            dayDiv.appendChild(dayNumber);
            daysDiv.appendChild(dayDiv);
        });
    }

    createDisplayMonthYear(containerDateAndBtnAdd){
        if (containerDateAndBtnAdd) {
            containerDateAndBtnAdd.innerHTML = '';
            let monthYearDiv = document.createElement('h1');
            let currentMonth = this.week[0].month;
            let currentYear = this.week[0].year;

            if (this.week[0].month !== this.week[5].month) {
                currentMonth = `${this.week[0].month} - ${this.week[5].month}`;
            }
            if (this.week[0].year !== this.week[5].year) {
                currentYear = `${this.week[0].year} - ${this.week[5].year}`;
            }

            if (this.week[0].month !== this.week[5].month && this.week[0].year !== this.week[5].year) {
                monthYearDiv.textContent = `${this.week[0].month} ${this.week[0].year} - ${this.week[5].month} ${this.week[5].year}`;
            }else {
                monthYearDiv.textContent = `${currentMonth} ${currentYear}`;
            }

            containerDateAndBtnAdd.appendChild(monthYearDiv);
        }
    }

    CardAppointment(endHourTime, endMinuteTime, reservationContainer, reservation, reservationBounds, previousTop) {
        let [HourStart, minuteStart] = reservation.Hour_start.split(':');
        const [HourEnd, minuteEnd] = reservation.Hour_end.split(':');
        let reservationDiv = document.createElement('div');
        let serviceName = document.createElement('h1');
        let topCard = (HourStart - endHourTime)*this.heightOfHour

        reservationDiv.className = 'CardAppointment';
        topCard = reservationBounds.prev !== null ? topCard + previousTop : topCard;
        if (reservationBounds.prev !== null) {
            if (reservationBounds.prev.Hour_end > reservation.Hour_start) {
                topCard = topCard - previousTop;
            }
        }
        if (reservationBounds.prev !== null && reservationBounds.prev.Hour_end < reservation.Hour_start) {
            const [prevHourStart, prevMinuteStart] = reservationBounds.prev.Hour_start.split(':');
            if (Number(prevHourStart) === 9) {
                topCard = topCard - previousTop;
            }
        }
        reservationDiv.style.position = 'relative';
        reservationDiv.style.top = `${(topCard)}vh`;

        reservationDiv.id = reservation.id_reservation;

        if (HourEnd - HourStart > 1){
            reservationDiv.style.height = `${((HourEnd - HourStart)*this.heightOfHour)}vh`;
        }else{
            reservationDiv.style.height = `${this.heightOfHour}vh`;
        }

        if (reservationBounds.prev !== null) {
            if (reservationBounds.prev.Hour_end > reservation.Hour_start) {

                reservationDiv.style.width = '49%';
            }
        }
        if (reservationBounds.next !== null) {
            if (reservationBounds.next.Hour_start < reservation.Hour_end) {
                reservationDiv.style.width = '45%';
                reservationDiv.style.left = '50%';
            }
        }

        serviceName.textContent = reservation.name_service;
        reservationDiv.appendChild(serviceName);
        reservationContainer.appendChild(reservationDiv);

        (Number(HourStart) === 9) ? HourStart = HourEnd : HourStart;
        return [Number(HourStart), topCard-9];
    }


    displayCalendar() {
        const dataReservation = JSON.parse(document.getElementById('dataReservation').getAttribute('data-reservation'));
        let daysDiv = document.getElementById('Days');
        let containerDateAndBtnAdd = document.getElementById('containerDateAndBtnAdd');
        let containerAppointments = document.getElementById('containerAppointments');

        // console.log(dataReservation);

        !daysDiv ? console.error('Element with id "Days" not found.') : daysDiv

        daysDiv.innerHTML = '';
        this.week.length === 0 ? this.getWeekDates(this.date) : this.week;

        this.createDisplayWeek(daysDiv);
        this.createDisplayMonthYear(containerDateAndBtnAdd);


        if (containerAppointments) {
            containerAppointments.innerHTML = '';

            this.week.forEach(day => {
                let appointmentDiv = document.createElement('div');
                const reservationsForDay = dataReservation.filter(reservation => reservation.Start_date_reservation === day.dateId);

                if (reservationsForDay.length > 0) {
                    let reservationContainer = document.createElement('div');
                    reservationContainer.className = 'reservation-container';

                    let endMinuteTime = 0;

                    let endHourTime = 9;
                    let previousTop = 0;
                    reservationsForDay.forEach((reservation, index) => {

                        const reservationBounds = {
                            prev: index > 0 ? reservationsForDay[index - 1] : null,
                            next: index < reservationsForDay.length - 1 ? reservationsForDay[index + 1] : null
                        };

                        [endHourTime, previousTop] = this.CardAppointment(endHourTime, endMinuteTime, reservationContainer, reservation, reservationBounds, previousTop);
                    });

                    appointmentDiv.appendChild(reservationContainer);
                } else {
                    let noReservation = document.createElement('p');
                    noReservation.textContent = 'Aucune réservation';
                    appointmentDiv.appendChild(noReservation);
                }

                containerAppointments.appendChild(appointmentDiv);
            });
        }
    }

    openModal = () => {
        let modal = document.getElementById('modal');
        const Cards = document.getElementsByClassName('CardAppointment');
        const form = document.getElementById('modalForm');
        const inputForm = document.getElementById('idCard');

        Array.from(Cards).forEach(card => {
            card.addEventListener("click", (e) => {
                console.log(card.getAttribute('id'));
                inputForm.value = card.getAttribute('id');
                form.submit();
            });
        });
    }


    closeModal() {
        let modal = document.getElementById('modal');
        let iconClose = document.getElementById('closeModal');
        iconClose.addEventListener("click", (e) => {
            modal.style.display = 'none';
        });

    }

}
let date = new Date();
let calendar = new Calendar(date);
calendar.displayCalendar();  // Affiche les jours dans la div "Days"

document.getElementById('NextWeek').addEventListener('click', () => {
    calendar.getNextWeek();
    calendar.displayCalendar();
    calendar.openModal();
    calendar.closeModal();

});

document.getElementById('PreviousWeek').addEventListener('click', ()=> {
    calendar.getPreviousWeek();
    calendar.displayCalendar();
    calendar.openModal();
    calendar.closeModal();

});

calendar.openModal();
calendar.closeModal();

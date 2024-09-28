class Calendar {
    constructor(date = new Date()) {
        this.date = date;
        this.week = [];
        this.heightOfHour = 9;
        this.topCard = 0;
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

    convertTimeToMinute(hour, minute) {
        return Number(hour) * 60 + Number(minute);
    }

    CardAppointment(reservationContainer, reservation) {
        let [HourStart, minuteStart] = reservation.Hour_start.split(':');
        const [HourEnd, minuteEnd] = reservation.Hour_end.split(':');
        let reservationDiv = document.createElement('div');
        let serviceName = document.createElement('h1');

        const startingTime = this.convertTimeToMinute(HourStart, minuteStart);
        const endingTime = this.convertTimeToMinute(HourEnd, minuteEnd);
        const nine = this.convertTimeToMinute(9, 0);


        reservationDiv.className = ` CardAppointment ${reservation.name_service}`;
        reservationDiv.id = reservation.id_reservation;
        // Getion de la taille des réservations
        reservationDiv.style.height = `${((endingTime - startingTime) / 15) * (this.heightOfHour / 4)}vh`;

        // Gestion de la position des réservations
        reservationDiv.style.position = "relative";
        let topCard = (((startingTime - nine)/15)*this.heightOfHour/4) - this.topCard;
        reservationDiv.style.top = `${topCard}vh`;

        serviceName.textContent = reservation.name_service;

        reservationDiv.appendChild(serviceName);
        reservationContainer.appendChild(reservationDiv);

        this.topCard += ((endingTime - startingTime)/15)*this.heightOfHour/4;
    }

    createLineTime(top = "") {
        let containerAppointments = document.getElementById('containerAppointments');
        if (!containerAppointments) {
            console.error('containerAppointments not found');
            return;
        }

        let lineTime = document.createElement('div');
        lineTime.className = 'lineTime';
        lineTime.style.position = "relative";
        lineTime.style.top = top;
        lineTime.style.width = "calc(100% - 1px)";
        lineTime.style.height = "1px";
        lineTime.style.backgroundColor = "rgb(0 0 0 / 14%)";

        containerAppointments.appendChild(lineTime);
    }



    displayCalendar() {
        const dataReservation = JSON.parse(document.getElementById('dataReservation').getAttribute('data-reservation'));
        let daysDiv = document.getElementById('Days');
        let containerDateAndBtnAdd = document.getElementById('containerDateAndBtnAdd');
        let containerAppointments = document.getElementById('containerAppointments');
        let containerWrapper = document.createElement('div');

        daysDiv.innerHTML = '';
        containerAppointments.innerHTML = '';
        containerWrapper.innerHTML = '';

        this.createLineTime("1.4vh");
        this.createLineTime("10.5vh");
        this.createLineTime("19.5vh");
        this.createLineTime("28.5vh");
        this.createLineTime("37.5vh");
        this.createLineTime("46.5vh");
        this.createLineTime("55.5vh");
        this.createLineTime("64.5vh");
        this.createLineTime("73.5vh");

        !daysDiv ? console.error('Element with id "Days" not found.') : daysDiv

        this.week.length === 0 ? this.getWeekDates(this.date) : this.week;

        this.createDisplayWeek(daysDiv);
        this.createDisplayMonthYear(containerDateAndBtnAdd);

        if (containerAppointments) {
            containerWrapper.className = 'container-wrapper';

            this.week.forEach(day => {
                let appointmentDiv = document.createElement('div');
                const reservationsForDay = dataReservation.filter(reservation => reservation.Start_date_reservation === day.dateId);

                if (reservationsForDay.length > 0) {
                    let reservationContainer = document.createElement('div');
                    reservationContainer.className = 'reservation-container';

                    this.topCard = 0;
                    reservationsForDay.forEach((reservation, index) => {
                        this.CardAppointment(reservationContainer, reservation);
                    });
                    appointmentDiv.appendChild(reservationContainer);
                } else {
                    let noReservation = document.createElement('p');
                    noReservation.textContent = 'Aucune réservation';
                    appointmentDiv.appendChild(noReservation);
                }

                // Ajouter chaque appointmentDiv dans le wrapper
                containerWrapper.appendChild(appointmentDiv);
            });

            // Ajouter le wrapper à containerAppointments
            containerAppointments.appendChild(containerWrapper);
        }
    }


    openModal () {
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
        if (iconClose) {
            iconClose.addEventListener("click", (e) => {
                modal.style.display = 'none';
            });
        }



    }

    addEvent() {
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

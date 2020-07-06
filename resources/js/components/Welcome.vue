
<template>
    <section id="calendar">
        <div class="container">
            <h1>Booking App</h1>
            <div class="calendar-area">
                <div class="calendar-left">
                    <p class="left-date">{{date}}</p>
                    <p class="left-day">{{dayOfTheWeek}}</p>
                    <p class="left-month" >{{monthWord}}/{{year}}</p>
                    <div class="set-month">
                        <p>Choose month</p>
                        <div class="prev-month">
                            <a class="mouse-a" @click="changeMonth(-1)"><</a>
<!--                            <p class="left-day">{{dayOfTheWeek}}</p>-->

                            <a class="mouse-a" @click="changeMonth(1)">></a>
                        </div>
                        <div class="text-center">
                        <p class="left-month text-center " >{{createMonthTitle(fickleMonth)}}/{{fickleYear}}</p>
                        </div>
                    </div>
                </div>
                <div class="calendar-right">
                    <div class="grid-container">
                        <div class="grid-item days-names">Mo</div>
                        <div class="grid-item days-names">Tu</div>
                        <div class="grid-item days-names">We</div>
                        <div class="grid-item days-names">Th</div>
                        <div class="grid-item days-names">Fr</div>
                        <div class="grid-item days-names">Sa</div>
                        <div class="grid-item days-names">Su</div>

                        <div class="grid-item " v-for="dayN in days" v-bind:class="{ 'active-item': dayN.active} " @click="showBookingModal(dayN)" ><a class="mouse-a" >{{dayN.date}}</a></div>
<!--                        <div class="grid-item"><a href="#">2</a></div>-->

                    </div>

                    <!-- BOOKING MODAL WINDOW -->
                    <BookingModal v-if="toggleModal" v-bind:dateIntervals2="dayIntervals"></BookingModal>
                    <!-- END OF BOOKING MODAL -->

                </div>
            </div>
        </div>

    </section>
</template>

<script>
    import BookingModal from "./Booking-modal";
    import { bus } from '../bus.js'
    import axios from "axios";


    export default {
        name: "Welcome",
        components: {BookingModal},
       data(){
            return{
                dateObj: new Date(),
                year:"",
                month:"",
                date:"",
                day:"",
                dayOfTheWeek:"",
                monthWord:"",
                fickleMonth:"",
                fickleDate:"",
                fickleDay:"",
                fickleYear:"",
                dayIntervals:[],
                days:[],
                intervals:[],
                toggleModal:false
            }

       },

        methods:{
            showBookingModal(date){
                if (date.active){
                    this.toggleModal=true
                    this.fickleDate=date.date
                    this.updateDayIntervals()
                    bus.$emit('mega-event', this.dayIntervals)
                }



            },
            getDaysInMonth(month, year) {
                let date = new Date(year, month, 1);
                let days = [];
                while (date.getMonth() === month) {
                    days.push({date:date.getDate(),month:date.getMonth(),year:date.getFullYear(),active:true});
                    date.setDate(date.getDate() + 1);
                }
                return days;
            },
            createMonthTitle(month){
                let months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                return   months[month];
            },
            createDayTitle(day){
                let days = ["Sunday","Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                return  days[day];
            },
            getToday() {
                let currentDate = new Date()
                this.year = currentDate.getFullYear()
                this.date = currentDate.getDate()
                this.month=currentDate.getMonth()
                this.day = currentDate.getDay()
                this.dayOfTheWeek = this.createDayTitle(this.day)
                this.monthWord = this.createMonthTitle(this.month)
                this.days = this.getDaysInMonth(this.month,this.year)
                this.fickleMonth = currentDate.getMonth()
                this.fickleYear=currentDate.getFullYear()
                this.dateObj=new Date();
                // this.updateListInterval()

            },
            getDaysInMonth(month, year) {
                let date = new Date(year, month, 1);
                let days = [];
                while (date.getMonth() === month) {
                    let NewInterval={date:date.getDate(),month:date.getMonth(),year:date.getFullYear(),active:false}
                    this.intervals.forEach(function (item) {
                        if(item.date==NewInterval.date && item.month==NewInterval.month && item.year==NewInterval.year)
                            NewInterval.active=true;
                    })
                    days.push(NewInterval);
                    date.setDate(date.getDate() + 1);
                }
                // console.log(days)
                return days;
            },
            checkInterval(item, index) {
                //   console.log(item.date,item.month,item.year)
                // console.log(this.fickleDate,this.fickleMonth,this.fickleYear)
                if(item.date==this.fickleDate && item.month==this.fickleMonth && item.year==this.fickleYear){
                    this.dayIntervals.push(item)

                }

            },
            updateListInterval(){
                this.dayIntervals=[];
                this.intervals.forEach(
                    this.checkInterval
                )
                console.log(this.dayIntervals)
                this.days = this.getDaysInMonth(this.dateObj.getMonth(),this.dateObj.getFullYear())

            },

            changeMonth(direction){
                let currentDate = new Date()
                if(direction===-1){
                    if(this.dateObj.valueOf()>=currentDate.valueOf()){
                        this.dateObj.setMonth(this.dateObj.getMonth()+direction)
                    }
                }else if(direction===1){
                    this.dateObj.setMonth(this.dateObj.getMonth()+direction)
                }

                    this.days = this.getDaysInMonth(this.dateObj.getMonth(),this.dateObj.getFullYear())

                    // this.dayOfTheWeek = this.createDayTitle(this.dateObj.getDay())





                // console.log(a)
               //  this.dateObj = new Date().setDate(a)
                this.fickleUpdate(this.dateObj)

                this.getApiData()
                 // console.log(this.dateObj)
                // console.log(currentDate)
            },
            fickleUpdate(date){
                this.fickleMonth = date.getMonth()
                this.fickleYear=date.getFullYear()
                this.fickleDate=date.getDate()
                this.fickleDay=date.getDay()
            },
            updateDayIntervals(){
                this.dayIntervals=[];
                this.intervals.forEach(
                    this.checkInterval
                )
            },

            getApiData(){
                axios.get(this.url+'/api/intervals',{
                    params: {
                        year: this.dateObj.getFullYear(),
                        month: this.dateObj.getMonth(),
                        prev:1,
                        next:1,
                    }
                }).then(response => {
                    // JSON responses are automatically parsed.
                    // console.log( response.data)
                    this.intervals=response.data
                    this.updateListInterval()
                })
                    .catch(e => {
                        console.log(e)
                    })
            }

        },mounted(){
            this.getApiData()
            this.getToday()
            bus.$on('toggle-modal', close => {
                    this.toggleModal=false
                this.getApiData()
                this.getToday()
            })
        }


    }
</script>

<style scoped>

</style>

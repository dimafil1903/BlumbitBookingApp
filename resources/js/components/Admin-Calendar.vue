<template>
    <div id="content-calendar">
        <section id="calendar">

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

                            <div class="grid-item " v-for="dayN in days" v-bind:class="{ 'active-item': dayN.active} " v-on:click="ChangeCurrent(dayN)" ><a class="mouse-a" href="#availability">{{dayN.date}}</a></div>
                            <!--                        <div class="grid-item"><a href="#">2</a></div>-->

                        </div>

                        <!-- BOOKING MODAL WINDOW -->
<!--                        <BookingModal></BookingModal>-->
                        <!-- END OF BOOKING MODAL -->

                    </div>
                </div>


        </section>

        <div id="set-availability">
            <h2>Set availability intervals</h2>

            <div class="set-availability-form">
                <div class="current-date">
                    <label>Date</label>
                    <label>{{createDayTitle(fickleDay)}}, {{createMonthTitle(fickleMonth)}}  {{fickleDate}}</label>
                </div>
                <div class="set-intervals" v-for="interval in dayIntervals">
                    <div class="set-from">
                        <label>From</label>
                        <input type="time"  v-bind:class="{ok_input: interval.valid }" v-model="interval.from" v-on:change="validateInput(interval)" v-bind:id="'from'+dayIntervals.indexOf(interval)"  name="from">
                    </div>
                    <div class="set-to">
                        <label>To</label>
                        <input type="time" v-bind:class="{ok_input: interval.valid }" v-model="interval.to" v-on:change="validateInput(interval) " v-bind:id="'to'+dayIntervals.indexOf(interval)"  name="to">
                    </div>
                    <div class="delete-interval">
                        <a class="mouse-a" v-on:click="deleteInterval(interval)"><i class="far fa-trash-alt"></i></a>
                    </div>
                </div>


                <a class="mouse-a" v-on:click="newInterval">+ New interval</a>

            </div  >

            <button @click="sendData" :disabled="buttonDisabled" class="submit-button-b mouse-a" >
                Apply
            </button>

        </div>
        <notifications group="intervals" />

    </div>
</template>

<script>
    import {bus} from "../bus";
    import axios from 'axios';

    export default {
        name: "Admin-Calendar",
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
                from:"",
                to:"",
                days:[],
                intervals:[],
                dayIntervals:[],
                tooggleNewInterval:false,
                buttonDisabled:false,
                border_red:{
                    border: "1px solid #e40d0d"
                 },
                border_default:{
                    border: "1px solid #dadada"
                }

            }

        },
        methods:{

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
            createMonthTitle(month){
                let months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                return   months[month];
            },
            createDayTitle(day){
                let days = ["Sunday","Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                return  days[day];
            },
            fickleUpdate(date){
                this.fickleMonth = date.getMonth()
                this.fickleYear=date.getFullYear()
                this.fickleDate=date.getDate()
                this.fickleDay=date.getDay()
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
                this.fickleUpdate(currentDate)
                this.dateObj=new Date();
                this.updateListInterval()

            },
            changeMonth(direction){

                let currentDate = this.dateObj
                let d= new Date()
                if(direction===-1){
                    if(this.dateObj.valueOf()>=d.valueOf()){
                        this.dateObj.setMonth(this.dateObj.getMonth()+direction)
                    }
                }else if(direction===1){
                    this.dateObj.setMonth(this.dateObj.getMonth()+direction)
                }

                // this.days = this.getDaysInMonth(this.dateObj.getMonth(),this.dateObj.getFullYear())
                this.fickleUpdate(this.dateObj)
                // this.dayOfTheWeek = this.createDayTitle(this.dateObj.getDay())
                this.getApiData()

                // console.log(a)
                //  this.dateObj = new Date().setDate(a)
                // console.log(this.dateObj)
                // console.log(currentDate)
            },
            updateListInterval(){
                this.dayIntervals=[];
                this.intervals.forEach(
                    this.checkInterval
                )
                this.days = this.getDaysInMonth(this.dateObj.getMonth(),this.dateObj.getFullYear())

            },
            deleteInterval(interval){
                this.dayIntervals.splice(this.dayIntervals.indexOf(interval), 1 )
                this.intervals.splice(this.dayIntervals.indexOf(interval), 1 )
                if(interval.id) {
                    axios.delete(this.url + '/api/intervals/' + interval.id)
                        .then(function (response) {
                            console.log(response);
                            self.$notify({
                                group: 'intervals',
                                title: 'Successful delete',
                                text: 'Interval was deleted successfully!',
                                type: 'success'
                            });
                            this.updateListInterval()
                        })
                        .catch(function (error) {
                            console.log(error);

                        });
                }

            },
             checkInterval(item, index) {
                 //   console.log(item.date,item.month,item.year)
                 // console.log(this.fickleDate,this.fickleMonth,this.fickleYear)
                if(item.date==this.fickleDate && item.month==this.fickleMonth && item.year==this.fickleYear){
                    this.dayIntervals.push(item)
                }

                },
            ChangeCurrent(date){
                this.dateObj.setDate(date.date);
                this.dateObj.setMonth(date.month);
                this.dateObj.setFullYear(date.year);
                this.fickleUpdate(this.dateObj)
                this.updateListInterval()

            },

            newInterval(){
                        this.intervals.push({
                            date:this.dateObj.getDate(),
                            month:this.dateObj.getMonth(),
                            year:this.dateObj.getFullYear(),
                            from:this.from,
                            to:this.to,
                            })

                this.updateListInterval()

                // console.log(this.intervals)
            },
            validateInput(input){

                let from=Date.parse("2002-03-19T"+input.from+":00")
                let to=Date.parse("2002-03-19T"+input.to+":00")
                   if(input.from=="" && input.to==""){
                       input.valid=false
                   }else {
                       input.valid = from <= to;
                   }
            },
            validateData(){
                let valid=true
                let val=this
                if (this.dayIntervals.length>0) {
                    this.dayIntervals.forEach(function (item, index) {
                        valid = true
                        let to = document.getElementById('to' + index);
                        let from = document.getElementById('from' + index);
                        val.validateInput(item)
                        if (!item.valid) {
                            valid = false
                            to.classList.add('error_input')
                            from.classList.add('error_input')
                            from.classList.remove('ok_input')
                            to.classList.remove('ok_input')
                            return valid
                        } else {
                            to.classList.remove('error_input')
                            from.classList.remove('error_input')
                            from.classList.add('ok_input')
                            to.classList.add('ok_input')
                        }

                    })
                }
                return valid


            },
            sendData(){

                    this.buttonDisabled = true
                    console.log(this.dayIntervals)
                    let self = this;
                    let propInterval
                    if (this.dayIntervals.length === 0) {
                        propInterval = [{
                            year: this.fickleYear,
                            date: this.fickleDate,
                            month: this.fickleMonth,
                            from: "",
                            to: ""

                        }]
                    } else {
                        propInterval = this.dayIntervals
                    }
                    if (this.validateData()) {
                        axios.post(this.url + '/api/intervals', {
                            intervals: propInterval,
                        })
                            .then(function (response) {
                                self.getApiData()
                                self.buttonDisabled = false;
                                self.$notify({
                                    group: 'intervals',
                                    title: 'Successful save',
                                    text: 'Intervals were set successfully!',
                                    type: 'success'
                                });

                            })
                            .catch(function (error) {
                                self.getApiData()
                                console.log(error);
                                self.buttonDisabled = false;
                            });

                    } else {
                        self.$notify({
                            group: 'intervals',
                            title: 'Saving failed',
                            text: 'Intervals have not been set!',
                            type: 'error'
                        });
                    }

                    // this.updateListInterval()


                },
            getApiData(){
                axios.get(this.url+'/api/intervals',{
                    params: {
                        year: this.dateObj.getFullYear(),
                        month: this.dateObj.getMonth()
                    }
                }).then(response => {
                    // JSON responses are automatically parsed.
                    console.log( response.data)
                    this.intervals=response.data
                    this.updateListInterval()

                })
                    .catch(e => {
                        console.log(e)
                    })
            }


        },mounted(){
            this.getToday()
           this.getApiData()
        },
        created() {




         }
    }
</script>

<style scoped>
    .error_input{
        border: 1px solid #e40d0d
    }
    .ok_input{
        border: 1px solid #dadada
    }
    .submit-button-b{
        border-color: transparent;
        margin-top: 30px;

        border-radius: 10px;
        background-color: var(--green);
        padding: 10px 30px;
        color: var(--dark-grey);
        font-weight: 500;
    }
    .submit-button-b:hover{
        background-color: var(--dark-grey);
        color: #fff;
        cursor: pointer;
    }
</style>

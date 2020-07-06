<template>
<div id="content-book-meeting">
    <div id="calendar-book-meeting">
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

        <div class="employees-database">
            <table>
                <tr>
                    <th class="w-10">Name</th>
                    <th class="w-10">Email</th>
                    <th class="w-10">Phone</th>
                    <th class="w-10"></th>
                </tr>
                <tr v-for="person in people">
                    <td>{{person.name}}</td>
                    <td>{{person.email}}</td>
                    <td>{{person.phone}}</td>
                    <td class="table-buttons"><a class="cancel"  v-on:click="deletePerson(person)"><i class="far fa-window-close"></i></a></td>
                </tr>


            </table>
            <div class="add-employee">
                <a @click="show">Add new person</a>
            </div>
        </div>
    </div>
    <modal name="hello-world" height="auto">
        <ModalAddPerson></ModalAddPerson>
    </modal>
    <div id="book-meeting">
        <div class="task-form-content">
            <h2>Book meeting</h2>
            <div class="a-area">
                <div class="a-left">
                    <form>
                        <div class="current-date">
                            <label>Date:</label>
                            <label>{{createDayTitle(dateObj.getDay())}}, {{createMonthTitle(dateObj.getMonth())}} {{dateObj.getDate()}}</label>
                        </div>
                        <label for="title">Title</label>
                        <input type="text" name="title" v-model="title" placeholder="Enter booking title">
                        <div class="set-intervals">
                            <div class="set-from">
                                <label>From</label>
                                <input type="time" v-model="from" name="from">
                            </div>
                            <div class="set-to">
                                <label>To</label>
                                <input type="time" v-model="to" name="to">
                            </div>

                        </div>
                        <label for="name">Invitees<span>*Hold Ctrl for multiple select</span></label>
                        <select name="name" multiple v-model="selectedPeople">
                            <option v-for="person in people" v-bind:name="'person'+person.id" v-bind:value="person.id" v-bind:id="'person'+person.id">{{person.name}}</option>

                        </select>
                        <label for="invitees">Custom invitees <span>*name:email</span></label>
                        <textarea rows="4" v-model="customInvites" name="invitees" maxlength="1000"></textarea>
                        <label for="description">Description</label>
                        <textarea rows="5" v-model="description" name="description" maxlength="1000"></textarea>
                        <div class="submit-button">
                            <a @click="createMeeting">Submit</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    import ModalAddPerson from "./ModalAddPerson";
    import {bus} from "../bus";
    import axios from "axios";
    export default {
        name: "Admin-Book-Meeting" ,
        components: {ModalAddPerson},
        data(){
            return{
                dateObj: new Date(),
                customInvites:"",
                selectedPeople:[],
                people:[],
                description:"",
                title:"",
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

                show () {
                    this.$modal.show('hello-world');
                },
                hide () {
                    this.$modal.hide('hello-world');
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
            createCustomInvites(customInvite){
               let temp = customInvite.split('\n');
               temp.forEach(function(item,index){
                   temp[index]=item.split(':')

               })
              return temp
            },
            createMeeting(){

                this.buttonDisabled = true

                let self = this;

                if (this.validateData()) {
                let customInvites= this.createCustomInvites(this.customInvites)
                    axios.post(this.url + '/api/booked_meetings', {
                        type:"admin",
                        title:self.title,
                        month:self.dateObj.getMonth(),
                        date:self.dateObj.getDate(),
                        year:self.dateObj.getFullYear(),
                        from:self.from,
                        to:self.to,
                        invites: JSON.stringify(self.selectedPeople),
                        custom_invites: JSON.stringify(customInvites),
                        description:self.description
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
            deletePerson(person){
                axios.delete(this.url + '/api/persons/'+person.id).then(response => {
                    // console.log(response.data)
                    // this.people=response.data

                }).catch(e=>{
                    console.log(e)
                }).finally(()=>{
                    this.getApiPeople()
                })
            },
            getApiPeople(){
                axios.get(this.url + '/api/persons').then(response => {
                    // console.log(response.data)
                    this.people=response.data

                })
                    .catch(e => {
                        console.log(e)

                    })
            },
            getApiData(){

            }


        },mounted(){
            this.getToday()
            this.getApiData()
            this.getApiPeople()
            bus.$on('close-AddModal', close => {
                this.hide()
                this.getApiPeople()

            })
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

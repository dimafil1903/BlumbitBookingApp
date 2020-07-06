<template>

    <div id="content-booked">
        <div class="booked-table">
            <h2>Booked Meetings</h2>
            <table>
                <tr>
                    <th class="w-20">Meeting</th>
                    <th class="w-10">Date & Time</th>
                    <th class="w-10">Name</th>
                    <th class="w-10">Email</th>
                    <th class="w-10">Phone</th>
                    <th class="w-30">Description</th>
                    <th class="w-10"></th>
                </tr>
                <tr v-for="bookedMeet in bookedMeetings">
                    <td class="w-20">{{bookedMeet.title}}</td>
                    <td class="w-10">
                        {{createMonthTitle(bookedMeet.interval.fullDate.getMonth())}}
                        {{bookedMeet.interval.fullDate.getDate()}},
                        {{bookedMeet.interval.fullDate.getFullYear()}}
                        <br>
                        {{bookedMeet.interval.from}}-{{bookedMeet.interval.to}}
                    </td>
                    <td class="w-10">{{bookedMeet.name}}</td>
                    <td class="w-10">{{bookedMeet.email}}</td>
                    <td class="w-10">{{bookedMeet.phone}}</td>
                    <td class="w-30">{{bookedMeet.description}}</td>
                    <td class="table-buttons">
                        <v-popover
                            placement="left-start"
                            trigger="manual"
                            :open="bookedMeet.id===onEdit"
                            offset="5"
                            :auto-hide="false"
                        >
                            <template slot="popover">
                                <div class="set-intervals">
                                    <div class="set-from"
                                         style="display: flex;align-items: baseline;">
                                        <label>Date</label>
                                        <datepicker v-model="bookedMeet.interval.fullDate"></datepicker>
                                    </div>
                                    <div class="set-from">
                                        <label>From</label>
                                        <input type="time" v-bind:class="{ok_input: bookedMeet.interval.valid }"
                                               v-model="bookedMeet.interval.from"
                                               v-on:change="validateInput(bookedMeet.interval)"
                                               v-bind:id="'from'+bookedMeetings.indexOf(bookedMeet)" name="from">
                                    </div>
                                    <div class="set-to">
                                        <label>To</label>
                                        <input type="time" v-bind:class="{ok_input: bookedMeet.interval.valid }"
                                               v-model="bookedMeet.interval.to"
                                               v-on:change="validateInput(bookedMeet.interval) "
                                               v-bind:id="'to'+bookedMeetings.indexOf(bookedMeet)" name="to">
                                    </div>

                                </div>
                                <button v-on:click="save(bookedMeet)" class="submit-button-b mouse-a">
                                    Apply
                                </button>
                            </template>

                        </v-popover>
                        <a class="reschedule  mouse-a" v-bind:id="'edit'+bookedMeet.id" @click="openEdit(bookedMeet)"
                        ><i class="far fa-clock"></i></a><a class="cancel mouse-a" @click="deleteMeeting(bookedMeet)"><i
                        class="far fa-window-close"></i></a></td>

                </tr>

            </table>
            <paginate v-if="lastPage && lastPage>1"
                      :page-count="lastPage"
                      :click-handler="clickCallback"
                      :prev-text="'<'"
                      :next-text="'>'"
                      :container-class="'pagination'">
            </paginate>
            <!--            <div class="pagination">-->
            <!--                <a href="#">{{first}}</a>-->
            <!--                <a href="#">{{nextPage}}</a>-->
            <!--                <a href="#">3</a>-->
            <!--                <a href="#">...</a>-->
            <!--                <a href="#">{{lastPage}}</a>-->
            <!--            </div>-->
        </div>
    </div>
</template>

<script>
    import axios from "axios";

    import Datepicker from "vuejs-datepicker/dist/vuejs-datepicker.esm.js";

    export default {
        name: "Admin-Meeting",
        components: {
            Datepicker
        },
        data() {
            return {
                date: null,
                bookedMeetings: [],
                currentPage: 1,
                prevPage: "",
                nextPage: "",
                lastPage: "",
                total: "",
                onEdit: "",
                from: "",
                to: "",
            }
        },
        methods: {
            clickCallback: function (page) {
                this.currentPage = page
                this.getApiData()
            },
            createMonthTitle(month) {
                let months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                return months[month];
            },
            openEdit(element) {
                this.onEdit = element.id
            },

            validateInput(input) {

                let from = Date.parse("2002-03-19T" + input.from + ":00")
                let to = Date.parse("2002-03-19T" + input.to + ":00")
                if (input.from == "" && input.to == "") {
                    input.valid = false
                } else {
                    input.valid = from <= to;
                }
            },
            deleteMeeting(el) {
                axios.delete(this.url + '/api/booked_meetings/' + el.id).then(response => {
                    this.getApiData()
                    // console.log(this.response.data)
                })
                    .catch(e => {
                        console.log(e)
                    })
            },
            save(el) {
                el.interval.date = el.interval.fullDate.getDate()
                el.interval.month = el.interval.fullDate.getMonth()
                el.interval.year = el.interval.fullDate.getFullYear()
                console.log(el.interval)
                axios.put(this.url + '/api/booked_meetings/' + el.id, {

                    interval: el.interval


                }).then(response => {
                    this.getApiData()
                    // console.log(this.response.data)
                })
                    .catch(e => {
                        console.log(e)
                    })
                this.onEdit = null


            },
            getApiData() {
                axios.get(this.url + '/api/booked_meetings', {
                    params: {
                        page: this.currentPage

                    }
                }).then(response => {


                    this.bookedMeetings = response.data.data
                    this.lastPage = response.data.last_page
                    this.bookedMeetings.forEach(function (item) {
                        item.interval.fullDate = new Date(item.interval.year, item.interval.month, item.interval.date)
                    })
                    // this.date= new Date()
                    console.log(this.bookedMeetings)
                })
                    .catch(e => {
                        console.log(e)
                    })
            }
        },
        mounted() {
            this.getApiData()
            this.popupItem = this.$el

        },


    }
</script>

<style>


    .tooltip {
        display: block !important;
        z-index: 10000;
    }


    .pagination {
        list-style: none;
    }

    .submit-button-b {
        border-color: transparent;
        display: flex;
        margin: 10px auto;
        border-radius: 10px;
        background-color: var(--green);
        padding: 10px 30px;
        color: var(--dark-grey);
        font-weight: 500;
    }

    .tooltip-inner {
        position: inherit;

    }

    .submit-button-b:hover {
        background-color: var(--dark-grey);
        color: #fff;
        cursor: pointer;
    }

    .wrapper {
        background: white;
        border: 1px solid #dadada;
        border-radius: 10px;
    }

    .set-intervals {
        margin: 10px;
    }
</style>

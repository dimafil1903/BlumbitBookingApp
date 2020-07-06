<template>
    <div id="availability" class="availability-window">
        <div class="availability-form-window">
            <div class="close-button">
                <a class="mouse-a" @click="toggleModal">x</a>
            </div>
            <div class="availability-form-content">
                <form id="modal-form" method="post" @submit.prevent="onSubmit">
                    <h2>Book meeting</h2>
                    <div class="a-area">
                        <div class="a-left">
                            <form>
                                <div class="column" :class="{ 'form-group--error': $v.title.$error }">
                                    <label for="title">Title</label>
                                    <input v-model="title" v-model.trim="$v.title.$model" type="text" required
                                           name="title" placeholder="Enter booking title">
                                    <div class="error" v-if="!$v.title.required && submitStatus === 'ERROR'">Title is
                                        required
                                    </div>

                                </div>
                                <div class="column" :class="{ 'form-group--error': $v.name.$error }">
                                    <label for="name">Name</label>
                                    <input v-model="name" v-model.trim="$v.name.$model" type="text" required name="name"
                                           class="form__input" placeholder="Enter your name">
                                    <div class="error" v-if="!$v.name.required && submitStatus === 'ERROR'">Name is
                                        required
                                    </div>
                                    <div class="error" v-if="!$v.name.minLength && submitStatus === 'ERROR'">Name must
                                        have at least {{$v.name.$params.minLength.min}} letters.
                                    </div>
                                </div>
                                <div class="column" :class="{ 'form-group--error': $v.email.$error }">
                                    <label for="email">Email</label>
                                    <input v-model="email" v-model.trim="$v.email.$model" type="email" required
                                           name="email" placeholder="Enter your email">
                                    <div class="error" v-if="!$v.email.required && submitStatus === 'ERROR'">Email is
                                        required
                                    </div>
                                    <div class="error" v-if="!$v.email.email && submitStatus === 'ERROR'">Email is
                                        wrong
                                    </div>
                                </div>
                                <div class="column" :class="{ 'form-group--error': $v.phone.$error }">
                                    <label for="phone">Phone</label>
                                    <input v-model="phone" type="tel" required name="phone"
                                           placeholder="Enter your phone number">
                                    <div class="error" v-if="!$v.phone.required && submitStatus === 'ERROR'">Phone is
                                        required
                                    </div>

                                </div>
                                <div class="column" :class="{ 'form-group--error': $v.desc.$error }">
                                    <label for="description">Description</label>
                                    <textarea v-model="desc" v-model.trim="$v.desc.$model" rows="5" name="description"
                                              maxlength="1000"></textarea>
                                    <div class="error" v-if="!$v.desc.required && submitStatus === 'ERROR'">Description
                                        is required
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="a-right">
                            <p>Select time</p>
                            <div class="a-right-scroll">
                                <a href="#" v-on:click="timeSelected(interval)"
                                   v-bind:class="{'selected':interval.selected}" v-for="interval in dateIntervals">{{interval.from}}-{{interval.to}}</a>
                            </div>
                            <div class="typo__p error center-el"
                                 v-if="submitStatus === 'ERROR' && Object.keys(this.selectedTime).length === 0 && this.selectedTime.constructor === Object">
                                Please choose interval
                            </div>

                        </div>
                    </div>

                    <button type="submit" :disabled="submitStatus === 'PENDING'"
                            class="align-self-center submit-button-b">Submit
                    </button>
                    <!--                    <div class="submit-button">-->
                    <!--                        <a href="#">submit</a>-->
                    <!--                    </div>-->

                    <div class="typo__p error center-el" v-if="submitStatus === 'ERROR'">Please fill the form
                        correctly.
                    </div>

                </form>
            </div>

        </div>
    </div>
</template>

<script>
    import {bus} from '../bus'
    import {required, minLength, email} from "vuelidate/lib/validators";
    import axios from "axios";

    export default {
        name: "Booking-modal",
        props: {
            dateIntervals2: {
                type: Array
            }
        },
        data() {
            return {
                visible: true,
                dateIntervals: [],
                selectedTime: {},
                title: "",
                name: "",
                email: "",
                phone: "",
                desc: "",
                submitStatus: null


            }
        },
        validations: {
            name: {
                required,
                minLength: minLength(3)
            },
            title: {
                required,
            },
            email: {
                required,
                email
            },
            phone: {
                required
            },
            desc: {
                required
            }
        },
        methods: {
            toggleModal() {
                this.visible = !this.visible
                this.dateIntervals.forEach(function (item) {
                    item.selected = false;
                })
                this.title = ""
                this.name = ""
                this.email = ""
                this.phone = ""
                this.desc = ""
                if (this.visible === false)
                    bus.$emit('toggle-modal', true)
            },
            timeSelected(selected) {
                let array = [];
                let that = this
                this.selectedTime = {}
                this.dateIntervals.forEach(function (item) {
                    item.selected = selected === item;
                    if (item.selected)
                        that.selectedTime = item
                    array.push(item)
                })
                this.dateIntervals = array
                // console.log(this.dateIntervals.pop())
                // timeSelected.f
            },
            submitForm() {

                this.onSubmit()
            },

            onSubmit() {
                // console.log('submit!')
                this.$v.$touch()
                if (this.$v.$invalid) {
                    this.submitStatus = 'ERROR'
                } else {
                    if (Object.keys(this.selectedTime).length === 0 && this.selectedTime.constructor === Object) {
                        this.submitStatus = 'ERROR'
                        return false
                    }
                    // do your submit logic here
                    this.submitStatus = 'PENDING'
                    axios.post(this.url + '/api/booked_meetings', {
                        type: 'user',
                        title: this.title,
                        name: this.name,
                        email: this.email,
                        phone: this.phone,

                        description: this.desc,
                        interval_id: this.selectedTime.id

                    }).then(response => {
                        console.log(response.data)
                        this.submitStatus = 'OK'
                    })
                        .catch(e => {
                            console.log(e)
                        })
                    setTimeout(() => {

                        this.toggleModal()
                    }, 500)
                }
            }
        },
        mounted() {

            this.dateIntervals = this.dateIntervals2
            // bus.$on('mega-event', dateIntervals => {
            //     this.dateIntervals=dateIntervals
            //     this.selectedTime={}
            //     console.log(dateIntervals)
            //     this.toggleModal()
            //
            // })
        }

    }

</script>

<style scoped>
    .selected {
        background-color: var(--green);
        color: #fff;
    }

    .submit-button-b {
        border-color: transparent;
        border-radius: 10px;
        background-color: var(--green);
        padding: 10px 30px;
        color: var(--dark-grey);
        font-weight: 500;
        margin: auto;
        display: flex;
    }

    .submit-button-b:hover {
        background-color: var(--dark-grey);
        color: #fff;
        cursor: pointer;
    }

    .column {
        display: flex;
        flex-direction: column;
    }

    .form-group--error {
        color: #bb0505;
    }

    .error {
        color: #bb0505;
    }

    .center-el {
        margin: auto;
        display: flex;
    }

    .form-group--error input {
        border-color: rgba(187, 5, 5, 0.99);
    }

    .form-group--error textarea {
        border-color: rgba(187, 5, 5, 0.99);
    }
</style>

<template>

                <form  style="padding: 30px">
                    <h2>Add new Person</h2>

                            <form id="modal-form" method="post" @submit.prevent="onSubmit">

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
                                <button type="submit" :disabled="submitStatus === 'PENDING'"
                                        class="align-self-center submit-button-b">Submit
                                </button>
                            </form>




                    <!--                    <div class="submit-button">-->
                    <!--                        <a href="#">submit</a>-->
                    <!--                    </div>-->

                    <div class="typo__p error center-el" v-if="submitStatus === 'ERROR'">Please fill the form
                        correctly.
                    </div>

                </form>

</template>

<script>
    import {bus} from '../bus'
    import {required, minLength, email} from "vuelidate/lib/validators";
    import axios from "axios";

    export default {
        name: "ModalAddPerson",
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
            email: {
                required,
                email
            },
            phone: {
                required
            },
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
                let that=this
                this.$v.$touch()
                if (this.$v.$invalid) {
                    this.submitStatus = 'ERROR'
                } else {

                    // do your submit logic here
                    this.submitStatus = 'PENDING'
                    axios.post(this.url + '/api/persons', {
                        name: this.name,
                        email: this.email,
                        phone: this.phone,
                    }).then(response => {
                        console.log(response.data)

                    })
                        .catch(e => {
                            console.log(e)

                        }).finally(function(){
                        that.submitStatus = 'OK'
                        setTimeout(() => {

                            bus.$emit('close-AddModal', true)
                        }, 500)
                    })

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

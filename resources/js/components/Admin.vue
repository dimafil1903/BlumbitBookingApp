<template>
    <section id="dashboard">
        <div class="sidebar-menu">
            <div class="sidebar-name">
                <p>Booking App</p>
            </div>
            <div class="sidebar-menu-links">
                <a  v-on:click="enableBlock(0)"><i class="far fa-calendar-alt"></i>Calendar</a>
                <a  v-on:click="enableBlock(1)"><i class="far fa-calendar-alt"></i>Book Meeting</a>
                <a  v-on:click="enableBlock(2)"><i class="far fa-bookmark"></i>Meetings</a>
                <a  v-on:click="enableBlock(3)"><i class="fas fa-list-ul"></i>Tasks</a>
            </div>
        </div>

        <admin-calendar  v-if="a"></admin-calendar>
        <admin-book-meeting v-if="b"></admin-book-meeting>
        <admin-meeting v-if="c"></admin-meeting>
        <admin-tasks v-if="d"></admin-tasks>
    </section>
</template>

<script>
    import AdminCalendar from "./Admin-Calendar";
    import AdminBookMeeting from "./Admin-Book-Meeting";
    import AdminMeeting from "./Admin-Meeting";
    import AdminTasks from "./Admin-Tasks";
    export default {

        name: "Admin",
        components: {AdminTasks, AdminMeeting, AdminBookMeeting, AdminCalendar},
        data(){
            return{
                a:true,
                b:false,
                c:false,
                d:false
            }
        },
        methods:{
            enableBlock:function(id){
                this.a=id===0
                this.b=id===1
                this.c=id===2
                this.d=id===3

            }
        }
    }
</script>

<style scoped>
a{
    cursor: pointer;
}
</style>

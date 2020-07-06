<template>
    <div id="content-tasks">
        <a id="show-completed" href="#" onclick="showcompleted()">Show completed tasks</a>
        <div class="tasks-table tasks-completed">
            <h2>Completed tasks</h2>
            <table>
                <tr>
                    <th class="w-20">Task</th>
                    <th class="w-10">Deadline</th>
                    <th class="w-10">Name</th>
                    <th class="w-10">Email</th>
                    <th class="w-10">Phone</th>
                    <th class="w-30">Description</th>
                    <th class="w-10">Status</th>
                    <th class="w-10">Delete</th>
                </tr>
                <tr>
                    <td>Подготовить документы для чего-нибудь</td>
                    <td>21.06.2020 желательно до обеда</td>
                    <td>Антон Попов</td>
                    <td>popov@mail.com</td>
                    <td>+123456785470</td>
                    <td>Мне нужны такие-то документы до 21.06.2020. Их нужно отправить до обеда.</td>
                    <td class="task-completed"><a href="#">Completed</a></td>
                    <td class="table-buttons"><a class="cancel" href="#"><i class="far fa-window-close"></i></a></td>
                </tr>
                <tr>
                    <td>Выплатить аванс</td>
                    <td>до 27.06.2020</td>
                    <td>Игорь Михайленко</td>
                    <td>popov@mail.com</td>
                    <td>+123456785470</td>
                    <td>Привет. Мы с тобой обсуждали выплату аванса. Мне как раз очень нужен, очень горит до 27 числа. Создал задачу, чтобы ты не забыла. Спасибо!</td>
                    <td class="task-completed"><a href="#">Completed</a></td>
                    <td class="table-buttons"><a class="cancel" href="#"><i class="far fa-window-close"></i></a></td>
                </tr>
                <tr>
                    <td>Выплатить аванс</td>
                    <td>до 27.06.2020</td>
                    <td>Игорь Михайленко</td>
                    <td>popov@mail.com</td>
                    <td>+123456785470</td>
                    <td>Привет. Мы с тобой обсуждали выплату аванса. Мне как раз очень нужен, очень горит до 27 числа. Создал задачу, чтобы ты не забыла. Спасибо!</td>
                    <td class="task-completed"><a href="#">Completed</a></td>
                    <td class="table-buttons"><a class="cancel" href="#"><i class="far fa-window-close"></i></a></td>
                </tr>
            </table>
            <div class="pagination">
                <a href="#">1</a>
                <a href="#">2</a>
                <a href="#">3</a>
                <a href="#">...</a>
            </div>
        </div>

        <div class="tasks-table">
            <h2>Active tasks</h2>
            <table>
                <tr>
                    <th class="w-20">Task</th>
                    <th class="w-10">Deadline</th>
                    <th class="w-10">Name</th>
                    <th class="w-10">Email</th>
                    <th class="w-10">Phone</th>
                    <th class="w-30">Description</th>
                    <th class="w-10">Status</th>
                    <th class="w-10">Delete</th>
                </tr>
                <tr>
                    <td>Подготовить документы для чего-нибудь</td>
                    <td>21.06.2020 желательно до обеда</td>
                    <td>Антон Попов</td>
                    <td>popov@mail.com</td>
                    <td>+123456785470</td>
                    <td>Мне нужны такие-то документы до 21.06.2020. Их нужно отправить до обеда.</td>
                    <td class="task-in-process"><a href="#">In process</a></td>
                    <td class="table-buttons"><a class="cancel" href="#"><i class="far fa-window-close"></i></a></td>
                </tr>
                <tr>
                    <td>Выплатить аванс</td>
                    <td>до 27.06.2020</td>
                    <td>Игорь Михайленко</td>
                    <td>popov@mail.com</td>
                    <td>+123456785470</td>
                    <td>Привет. Мы с тобой обсуждали выплату аванса. Мне как раз очень нужен, очень горит до 27 числа. Создал задачу, чтобы ты не забыла. Спасибо!</td>
                    <td class="task-new"><a href="#">New</a></td>
                    <td class="table-buttons"><a class="cancel" href="#"><i class="far fa-window-close"></i></a></td>
                </tr>
                <tr>
                    <td>Выплатить аванс</td>
                    <td>до 27.06.2020</td>
                    <td>Игорь Михайленко</td>
                    <td>popov@mail.com</td>
                    <td>+123456785470</td>
                    <td>Привет. Мы с тобой обсуждали выплату аванса. Мне как раз очень нужен, очень горит до 27 числа. Создал задачу, чтобы ты не забыла. Спасибо!</td>
                    <td class="task-new"><a href="#">New</a></td>
                    <td class="table-buttons"><a class="cancel" href="#"><i class="far fa-window-close"></i></a></td>
                </tr>
            </table>
            <div class="pagination">
                <a href="#">1</a>
                <a href="#">2</a>
                <a href="#">3</a>
                <a href="#">...</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Admin-Tasks"
    }
</script>

<style scoped>

</style>

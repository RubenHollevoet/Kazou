Vue.component('groupstack-item', {
    props: ['group'],
    template: '<li>{{ group.text }}</li>'
});

Vue.component('groupsavaliable-item', {
    props: ['group'],
    template: '<span class="btn btn-default" v-bind:data-id="group.id" v-on:click="fetchGroups">{{ group.name }}</span>'
});

var app = new Vue({
    el: '#app',
    data: {
        page: 0,
        groupStack: [
            {id: 0, text: 'Vegetables'},
            {id: 1, text: 'Cheese'},
            {id: 2, text: 'Whatever else humans are supposed to eat'}
        ],
        activeGroups: []
    },
    methods: {
        prevStep: function () {
            this.page--
        },
        nextStep: function () {
            this.page++
        },
        submit: function () {
            console.log('todo: submit onkosten')
        },
        fetchGroups: function (id = 0) {
            var self = this;
            axios.get('//kazourmt.dev.be/app_dev.php/expenses/api/getChildGroups?group=' + id.toString())
                .then(function (response) {
                    self.activeGroups = response.data.data;
                    console.log('ddd');
                })
                .catch(function (error) {
                    self.fetchError = error;
                    console.log(error);
                })
        }
    },
    mounted: function () {
        this.fetchGroups(0);
    }
});

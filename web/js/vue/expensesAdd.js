Vue.component('groupstack-item', {
    props: ['group'],
    template: '<li v-bind:data-id="group.id">{{ group.text }} <span class="btn btn-danger fa fa-remove" v-on:click="revertSelectors"></span></li>',
    methods: {
        revertSelectors: function (evt) {
            var newArrayLength = $(evt.currentTarget.parentElement).index();
            this.$root.groupStack = this.$root.groupStack.slice(0, newArrayLength);

            var prevParentId = this.$root.groupStack[newArrayLength - 1] ? this.$root.groupStack[newArrayLength - 1].id : 0;
            this.$root.fetchGroups(prevParentId);
        }
    }
});

Vue.component('groupsavaliable-item', {
    props: ['group'],
    template: '<span class="btn btn-default" v-bind:data-id="group.id" v-on:click="loadNewGroups">{{ group.name }}</span>',
    methods: {
        loadNewGroups: function (evt) {
            this.$root.groupStack.push({id: this.group.id, text: this.group.name});
            this.$root.fetchGroups(evt.currentTarget.dataset.id)
        },
    }
});

var app = new Vue({
    el: '#expenses_app',
    data: {
        page: 0,
        groupStack: [],
        activeGroups: [],
        tripData: {
            from: '',
            to: '',
            date: '',
        }

    },
    methods: {
        prevStep: function () {
            this.page--;
        },
        nextStep: function () {
            this.page++;
        },
        submit: function () {
            console.log('todo: submit onkosten');

            console.log(document.getElementById('from'));

            var json = {
                firstName: 'Fred',
                lastName: 'Flintstone'
            };


            axios.post('//kazourmt.dev.be/app_dev.php/expenses/api/createTrip', this.tripData)
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        fetchGroups: function (id = 0) {
            var self = this;
            axios.get('//kazourmt.dev.be/app_dev.php/expenses/api/getChildGroups?group=' + id.toString())
                .then(function (response) {
                    self.activeGroups = response.data.data;

                })
                .catch(function (error) {
                    self.fetchError = error;
                })
        }
    },
    mounted: function () {
        this.fetchGroups(0);
    }
});

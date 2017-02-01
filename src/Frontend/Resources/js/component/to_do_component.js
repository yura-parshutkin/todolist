import CONSTANT from './../constant';
import $ from 'jquery';
import taskListView from './../template/task_list.hbs';
import filterListView from './../template/filter_list.hbs';
import Task from './../model/task';
import director from 'director';

export default class ToDoComponent {
    constructor(el, taskStore) {
        const self           = this;
        self.$root           = $(el);
        self.$list           = self.$root.find('.js-task-list');
        self.$panel          = self.$root.find('.js-panel');
        self.$clearCompleted = self.$panel.find('.clear-completed');
        self.$count          = self.$panel.find('.todo-count > strong');
        self.$filters        = self.$root.find('.js-filter-list');
        self.taskStore       = taskStore;

        self.bindActions();
        self.bindRoutes();
        self.taskStore.fetch();
    }

    bindActions() {
        const self = this;

        self.taskStore.bind('change', function(){
            self.render();
        });

        self.$root.on('keypress', '.js-new', function(e) {
            if (e.keyCode !== CONSTANT.KEY_CODE_ENTER) {
                return;
            }

            const $el   = $(this);
            const value = $el.val();
            $el.val('');
            self.taskStore.add(new Task(null, value, false));
        });

        self.$root.on('click', '.js-complete-all', function() {
            const $el        = $(this);
            const isComplete = !$el.data('complete');
            $el.data('complete', isComplete);

            if (isComplete) {
                self.taskStore.completeAll();
            } else {
                self.taskStore.unCompleteAll();
            }
        });

        self.$root.on('click', '.js-remove', function(){
            const task = self.taskStore.find($(this).data('id'));
            self.taskStore.remove(task);
        });

        self.$list.on('click', '.js-check-complete', function(e) {
            e.preventDefault();
            const task       = self.taskStore.find($(this).data('id'));
            task.isCompleted = !task.isCompleted;
            self.taskStore.update(task);
        });

        self.$panel.on('click', '.js-clear-completed', function(e) {
            e.preventDefault();
            self.taskStore.removeComplete();
        });
    }

    bindRoutes() {
        const self = this;
        const map  = {
            '/:filter': function(filter) {
                self.filter = filter;
                self.render();
            }
        };

        director.Router(map).init('/all');
    }

    render() {
        const self = this;
        self.$list.html(taskListView({
            tasks: self.taskStore.findTasks(self.filter)
        }));

        self.$filters.html(filterListView({
            filters: self.getFilters()
        }));

        self.$count.text(self.taskStore.countUnCompleted());

        if (self.taskStore.countCompleted() > 0) {
            self.$clearCompleted.show();
        } else {
            self.$clearCompleted.hide();
        }
    }

    getFilters() {
        const self    = this;
        const filters = [
            { route: 'all', label: 'All', isChecked: false },
            { route: 'active', label: 'Active', isChecked: false },
            { route: 'completed', label: 'Completed', isChecked: false }
        ];

        filters.find(filter => filter.route === self.filter).isChecked = true;

        return filters;
    }
}
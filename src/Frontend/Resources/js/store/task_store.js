import microevent from 'microevent';
import Task from './../model/task';

const filters = {
    'all':       task => true,
    'active':    task => !task.isCompleted,
    'completed': task => task.isCompleted
};

class TaskStore {
    constructor(restProvider) {
        this.tasks        = [];
        this.restProvider = restProvider;
    }

    dispatch() {
        this.trigger('change');
    }

    findTasks(filter) {
        return this.tasks.filter(filters[filter]);
    }

    fetch() {
        const self = this;

        self.restProvider.fetch(function(tasks) {
            self.tasks =  tasks.map(function(task) {
                return new Task(
                    task.id,
                    task.name,
                    task.isCompleted
                );
            });

            self.dispatch();
        });
    }

    add(task) {
        const self = this;

        self.restProvider.add(task, function(response) {
            task.id = response.id;
            self.tasks.push(task);
            self.dispatch();
        });
    }

    findIndex(findTask) {
        const taskIndex = this.tasks.findIndex(task => task.isEqual(findTask));

        if (taskIndex === -1) {
            throw new Error('Task is not found');
        }

        return taskIndex;
    }

    update(task) {
        const self = this;

        self.restProvider.update(task, function() {
            self.dispatch();
        });
    }

    remove(removeTask) {
        const self      = this;
        const taskIndex = self.findIndex(removeTask);

        self.restProvider.remove(removeTask, function() {
            self.tasks.splice(taskIndex, 1);
            self.dispatch();
        });
    }

    find(id) {
        return this.tasks.find(task => task.id === id);
    }

    completeAll() {
        const self = this;

        self.restProvider.completeAll(function() {
            self.tasks.forEach(function(task){
                task.isCompleted = true;
            });

            self.dispatch();
        });
    }

    unCompleteAll() {
        const self = this;

        self.restProvider.unCompleteAll(function() {
            self.tasks.forEach(function(task){
                task.isCompleted = false;
            });

            self.dispatch();
        });
    }

    removeComplete() {
        const self = this;

        self.restProvider.removeComplete(function() {
            self.tasks = self.tasks.filter(task => !task.isCompleted);
            self.dispatch();
        });
    }

    countCompleted() {
        return this.tasks.filter(task => task.isCompleted).length;
    }

    countUnCompleted() {
        return this.tasks.filter(task => !task.isCompleted).length;
    }
}

microevent.mixin(TaskStore);

export default TaskStore;
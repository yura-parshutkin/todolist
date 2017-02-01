export default class Task {
    constructor(id, name, isCompleted) {
        this.id          = id;
        this.name        = name;
        this.isCompleted = isCompleted;
    }

    isEqual(task) {
        return this.id === task.id;
    }
}
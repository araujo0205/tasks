declare namespace App {
    namespace Data {
        export type TaskData = {
            id: number;
            title: string;
            description: string | null;
            status: App.Enums.TaskStatus;
            created_at: string;
        };
    }
    namespace Enums {
        export type TaskStatus = 'inbox' | 'waiting' | 'done';
    }
}

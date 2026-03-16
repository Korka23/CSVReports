<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Контроль выполнения процессов</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css">
</head>
<body class="bg-gray-50 font-sans">
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Контроль выполнения процессов</h1>
    <table class="table-auto w-full border-collapse border border-gray-300">
        <thead>
        <tr class="bg-gray-100">
            <th class="border border-gray-300 px-2 py-1">Дата процесса</th>
            <th class="border border-gray-300 px-2 py-1">Время выполнения (сек)</th>
            <th class="border border-gray-300 px-2 py-1">PID</th>
            <th class="border border-gray-300 px-2 py-1">Статус</th>
            <th class="border border-gray-300 px-2 py-1">Файл</th>
        </tr>
        </thead>
        <tbody>
        @forelse($processes as $process)
            <tr @if($process->status === 'Ошибка') class="bg-red-200" @endif>
                <td class="border border-gray-300 px-2 py-1">{{ $process->start_datetime }}</td>
                <td class="border border-gray-300 px-2 py-1">{{ $process->exec_time ?? '-' }}</td>
                <td class="border border-gray-300 px-2 py-1">{{ $process->pid }}</td>
                <td class="border border-gray-300 px-2 py-1">{{ $process->status }}</td>
                <td class="border border-gray-300 px-2 py-1">
                    @if($process->file_save_path && $process->status === \App\Enums\ProcessStatusEnum::COMPLETED)
                        <a href="{{ route('report.download', $process->id) }}" class="text-blue-600 underline">Скачать</a>
                    @else
                        -
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center py-2">Нет процессов</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
</body>
</html>

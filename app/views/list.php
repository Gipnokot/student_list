<h1 class="mt-4">Список абитуриентов</h1>
<a class="btn btn-primary mb-4" href="index.php?action=logout">Выйти</a>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success" role="alert">
        <?= $_SESSION['success_message']; ?>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<!-- Форма поиска -->
<form action="index.php" method="GET" class="mb-4">
    <input type="hidden" name="action" value="list">
    <input type="text" name="search" placeholder="Поиск..." class="form-control" value="<?= htmlspecialchars($search ?? ''); ?>">
    <button type="submit" class="btn btn-secondary mt-2">Найти</button>
</form>

<?php if (!empty($search)): ?>
    <h2>Показаны только абитуриенты, найденные по запросу «<?= htmlspecialchars($search); ?>».</h2>
    <a href="index.php?action=list">Показать всех абитуриентов</a>
<?php endif; ?>

<table class="table table-striped">
    <thead>
        <tr>
            <th><a href="index.php?action=list&order=<?= $order === 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?= htmlspecialchars($search ?? ''); ?>">Имя</a></th>
            <th><a href="index.php?action=list&order=<?= $order === 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?= htmlspecialchars($search ?? ''); ?>">Фамилия</a></th>
            <th><a href="index.php?action=list&order=<?= $order === 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?= htmlspecialchars($search ?? ''); ?>">Номер группы</a></th>
            <th><a href="index.php?action=list&order=<?= $order === 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?= htmlspecialchars($search ?? ''); ?>">Баллы</a></th>
        </tr>
    </thead>
    <tbody>
        <?php if (isset($students) && is_array($students)): ?>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= htmlspecialchars($student->first_name); ?></td>
                    <td><?= htmlspecialchars($student->last_name); ?></td>
                    <td><?= htmlspecialchars($student->group_number); ?></td>
                    <td><?= htmlspecialchars($student->exam_score); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Нет данных</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Пагинация -->
<nav aria-label="Page navigation example">
    <ul class="pagination">
        <?php for ($i = 1; $i <= ceil($totalStudents / 50); $i++): ?>
            <li class="page-item <?= ($currentPage == $i) ? 'active' : ''; ?>">
                <a class="page-link" href="index.php?action=list&page=<?= $i; ?>&search=<?= htmlspecialchars($search ?? ''); ?>"><?= $i; ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
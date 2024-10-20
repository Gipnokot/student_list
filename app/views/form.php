<h1 class="mt-4">Регистрация абитуриента</h1>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success" role="alert">
        <?= $_SESSION['success_message']; ?>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger" role="alert">
        <ul>
            <?php foreach ($errors as $field => $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="index.php?action=register" method="POST">
    <div class="form-group">
        <label for="first_name">Имя</label>
        <input type="text" class="form-control <?= isset($errors['first_name']) ? 'is-invalid' : ''; ?>" id="first_name" name="first_name" value="<?= htmlspecialchars($student->first_name ?? ''); ?>" required>
    </div>
    <div class="form-group">
        <label for="last_name">Фамилия</label>
        <input type="text" class="form-control <?= isset($errors['last_name']) ? 'is-invalid' : ''; ?>" id="last_name" name="last_name" value="<?= htmlspecialchars($student->last_name ?? ''); ?>" required>
    </div>
    <div class="form-group">
        <label for="gender">Пол</label>
        <select class="form-control <?= isset($errors['gender']) ? 'is-invalid' : ''; ?>" id="gender" name="gender" required>
            <option value="male" <?= ($student->gender ?? '') === 'male' ? 'selected' : ''; ?>>Мужской</option>
            <option value="female" <?= ($student->gender ?? '') === 'female' ? 'selected' : ''; ?>>Женский</option>
        </select>
    </div>
    <div class="form-group">
        <label for="group_number">Номер группы</label>
        <input type="text" class="form-control <?= isset($errors['group_number']) ? 'is-invalid' : ''; ?>" id="group_number" name="group_number" value="<?= htmlspecialchars($student->group_number ?? ''); ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?= htmlspecialchars($student->email ?? ''); ?>" required>
    </div>
    <div class="form-group">
        <label for="exam_score">Суммарное число баллов на ЕГЭ</label>
        <input type="number" class="form-control <?= isset($errors['exam_score']) ? 'is-invalid' : ''; ?>" id="exam_score" name="exam_score" value="<?= htmlspecialchars($student->exam_score ?? ''); ?>" required>
    </div>
    <div class="form-group">
        <label for="birth_year">Год рождения</label>
        <input type="number" class="form-control <?= isset($errors['birth_year']) ? 'is-invalid' : ''; ?>" id="birth_year" name="birth_year" value="<?= htmlspecialchars($student->birth_year ?? ''); ?>" required>
    </div>
    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" id="is_local" name="is_local" value="1" <?= ($student->is_local ?? false) ? 'checked' : ''; ?>>
        <label class="form-check-label" for="is_local">Местный</label>
    </div>
    <button type="submit" class="btn btn-primary">Отправить</button>
</form>
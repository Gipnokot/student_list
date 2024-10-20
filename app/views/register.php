<h2>Регистрация студента</h2>
<form action="index.php?action=register" method="post">
    <div class="mb-3">
        <label for="firstName" class="form-label">Имя</label>
        <input type="text" id="firstName" name="first_name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="lastName" class="form-label">Фамилия</label>
        <input type="text" id="lastName" name="last_name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="gender" class="form-label">Пол</label>
        <select id="gender" name="gender" class="form-select" required>
            <option value="male">Мужской</option>
            <option value="female">Женский</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="groupNumber" class="form-label">Номер группы</label>
        <input type="text" id="groupNumber" name="group_number" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="score" class="form-label">Суммарное число баллов</label>
        <input type="number" id="score" name="exam_score" class="form-control" min="0" max="300" required>
    </div>
    <div class="mb-3">
        <label for="birthYear" class="form-label">Год рождения</label>
        <input type="number" id="birthYear" name="birth_year" class="form-control" min="1900" max="2024" required>
    </div>
    <div class="mb-3">
        <label for="locality" class="form-label">Местный/Иногородний</label>
        <select id="locality" name="is_local" class="form-select" required>
            <option value="local">Местный</option>
            <option value="non-local">Иногородний</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Зарегистрировать</button>
</form>
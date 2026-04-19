<?php require_once APP_ROOT . '/views/inc/components/header.php'; ?>

  
<style>
    .result-card {
        background: linear-gradient(180deg, #ffffff 0%, #fafafa 100%);
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 22px;
        margin-top: 16px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.07);
    }

    .result-title {
        margin: 0 0 14px;
        font-size: 22px;
        font-weight: 700;
        letter-spacing: 0.2px;
        color: #0f172a;
    }

    .result-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        background: #ffffff;
    }

    .result-table th,
    .result-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #edf1f5;
        text-align: left;
        vertical-align: middle;
        font-size: 14px;
    }

    .result-table th {
        background: #f8fafc;
        font-weight: 700;
        color: #111827;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.4px;
    }

    .result-table tbody tr:nth-child(even) {
        background: #fcfdff;
    }

    .result-table tbody tr:hover {
        background: #f1f5f9;
        transition: background-color 0.2s ease;
    }

    .result-table tbody tr:last-child td {
        border-bottom: none;
    }

    .result-image {
        width: 84px;
        height: 84px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #d1d5db;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.12);
    }

    .empty-text {
        color: #64748b;
        margin: 0;
        padding: 16px;
        border: 1px dashed #cbd5e1;
        border-radius: 10px;
        background: #f8fafc;
    }

    .filters-row {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 14px;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        min-width: 180px;
        gap: 6px;
    }

    .filter-group label {
        font-size: 12px;
        font-weight: 700;
        color: #334155;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }

    .filter-select {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 9px 10px;
        font-size: 14px;
        color: #0f172a;
        background: #ffffff;
    }

    .filter-select:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .no-filter-results {
        display: none;
        margin-top: 12px;
        color: #475569;
        font-size: 14px;
        font-weight: 500;
    }

    .back-to-form-btn {
        margin-top: 16px;
        width: 170px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        border: none;
        border-radius: 10px;
        padding: 10px 14px;
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: #ffffff;
        font-weight: 600;
        transition: transform 0.15s ease, box-shadow 0.2s ease;
    }

    .back-to-form-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(185, 28, 28, 0.28);
    }

    .back-to-form-btn:active {
        transform: translateY(0);
    }

    @media (max-width: 768px) {
        .result-card {
            padding: 16px;
        }

        .result-table th,
        .result-table td {
            padding: 11px 10px;
            font-size: 13px;
        }

        .result-image {
            width: 64px;
            height: 64px;
        }

        .filter-group {
            min-width: 100%;
        }

        .back-to-form-btn {
            width: 100%;
        }
    }
</style>

    <!-- Content will be loaded here -->

        <div class="result-card">
            <h2 class="result-title">Submitted Data</h2>

            <?php if (!empty($data['applications'])) : ?>
                <div class="filters-row">
                    <div class="filter-group">
                        <label for="genderFilter">Filter by Gender</label>
                        <select id="genderFilter" class="filter-select">
                            <option value="">All Genders</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="hobbyFilter">Filter by Hobby</label>
                        <select id="hobbyFilter" class="filter-select">
                            <option value="">All Hobbies</option>
                            <option value="reading books">Reading books</option>
                            <option value="play games">Play games</option>
                            <option value="collect stamps">Collect stamps</option>
                            <option value="watch tv">Watch TV</option>
                        </select>
                    </div>
                </div>

                <table class="result-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Description</th>
                            <th>Hobbies</th>
                            <th>Photo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['applications'] as $application) : ?>
                            <?php
                                $hobbies = [];

                                if (!empty($application->reading_books)) {
                                    $hobbies[] = 'Reading books';
                                }

                                if (!empty($application->play_games)) {
                                    $hobbies[] = 'Play games';
                                }

                                if (!empty($application->collect_stamps)) {
                                    $hobbies[] = 'Collect stamps';
                                }

                                if (!empty($application->watch_tv)) {
                                    $hobbies[] = 'Watch TV';
                                }

                                $hobbiesText = !empty($hobbies) ? implode(', ', $hobbies) : '-';
                            ?>
                            <tr data-gender="<?php echo htmlspecialchars(strtolower(trim((string) ($application->gender ?? '')))); ?>" data-hobbies="<?php echo htmlspecialchars(strtolower($hobbiesText)); ?>">
                                <td><?php echo htmlspecialchars($application->name ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($application->email ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($application->gender ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($application->description ?? ''); ?></td>
                                <td>
                                    <?php
                                        echo htmlspecialchars($hobbiesText);
                                    ?>
                                </td>
                                <td>
                                    <?php if (!empty($application->image)) : ?>
                                        <img
                                            class="result-image"
                                            src="<?php echo URL_ROOT; ?>/uploads/applicantPhotos/<?php echo rawurlencode($application->image); ?>"
                                            alt="Applicant photo"
                                        />
                                    <?php else : ?>
                                        No image
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p id="noFilterResults" class="no-filter-results">No records match the selected filters.</p>
            <?php else : ?>
                <p class="empty-text">No submission data available.</p>
            <?php endif; ?>
        </div>
        <button class="back-to-form-btn" onclick="window.location.href='<?php echo URL_ROOT; ?>/test/index'">Back to Form</button>

    </main>
    </div>

    <div class="backdrop" id="backdrop" hidden></div>

    <script>
        (function () {
            const genderFilter = document.getElementById('genderFilter');
            const hobbyFilter = document.getElementById('hobbyFilter');
            const tableRows = document.querySelectorAll('.result-table tbody tr');
            const noFilterResults = document.getElementById('noFilterResults');

            if (!genderFilter || !hobbyFilter || tableRows.length === 0) {
                return;
            }

            const applyFilters = () => {
                const selectedGender = genderFilter.value.trim().toLowerCase();
                const selectedHobby = hobbyFilter.value.trim().toLowerCase();
                let visibleRows = 0;

                tableRows.forEach((row) => {
                    const rowGender = (row.dataset.gender || '').toLowerCase();
                    const rowHobbies = (row.dataset.hobbies || '').toLowerCase();

                    const genderMatches = !selectedGender || rowGender === selectedGender;
                    const hobbyMatches = !selectedHobby || rowHobbies.includes(selectedHobby);
                    const isVisible = genderMatches && hobbyMatches;

                    row.style.display = isVisible ? '' : 'none';

                    if (isVisible) {
                        visibleRows += 1;
                    }
                });

                if (noFilterResults) {
                    noFilterResults.style.display = visibleRows === 0 ? 'block' : 'none';
                }
            };

            genderFilter.addEventListener('change', applyFilters);
            hobbyFilter.addEventListener('change', applyFilters);
        })();
    </script>

    <script src="<?php echo URL_ROOT; ?>/js/components/sidebar.js"></script>
<?php require_once APP_ROOT . '/views/inc/components/footer.php'; ?>
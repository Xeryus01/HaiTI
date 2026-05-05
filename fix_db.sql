-- Fix piket schedules table
-- Delete all existing records
DELETE FROM piket_schedules;

-- Insert new weekly schedules
INSERT INTO piket_schedules (week_start_date, technician_1, technician_2, technician_3, created_at, updated_at) VALUES
('2026-05-05', 'Fadil Rahman', 'Marko Santoso', 'Eji Wijaya', datetime('now'), datetime('now')),
('2026-05-12', 'Marko Santoso', 'Eji Wijaya', 'Mesra Putri', datetime('now'), datetime('now')),
('2026-05-19', 'Eji Wijaya', 'Mesra Putri', 'Fadil Rahman', datetime('now'), datetime('now')),
('2026-05-26', 'Mesra Putri', 'Fadil Rahman', 'Marko Santoso', datetime('now'), datetime('now')),
('2026-06-02', 'Fadil Rahman', 'Marko Santoso', 'Eji Wijaya', datetime('now'), datetime('now')),
('2026-06-09', 'Marko Santoso', 'Eji Wijaya', 'Mesra Putri', datetime('now'), datetime('now')),
('2026-06-16', 'Eji Wijaya', 'Mesra Putri', 'Fadil Rahman', datetime('now'), datetime('now')),
('2026-06-23', 'Mesra Putri', 'Fadil Rahman', 'Marko Santoso', datetime('now'), datetime('now')),
('2026-06-30', 'Fadil Rahman', 'Marko Santoso', 'Eji Wijaya', datetime('now'), datetime('now')),
('2026-07-07', 'Marko Santoso', 'Eji Wijaya', 'Mesra Putri', datetime('now'), datetime('now')),
('2026-07-14', 'Eji Wijaya', 'Mesra Putri', 'Fadil Rahman', datetime('now'), datetime('now')),
('2026-07-21', 'Mesra Putri', 'Fadil Rahman', 'Marko Santoso', datetime('now'), datetime('now'));
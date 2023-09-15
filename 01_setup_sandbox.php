<?php

function make_a_directory($dir) {
	if (!is_dir($dir)) {
		if (mkdir($dir, 0755, true)) {
			echo "The directory \"$dir\" has been successfully created\n";
		} else {
			echo "An error has occured\n";
			die("Failed to create directory...\n");
		}
	} else {
		echo "The directory \"$dir\" already exists\n";
	}
}

function get_files_to_move($source_directory) {
	$files_to_move = [];
	$contents = scandir($source_directory);
	
	foreach ($contents as $file) {
		if ($file !== '.donotdelete' && is_file($source_directory . '/' . $file)) {
			$files_to_move[] = $source_directory . '/' . $file;
		} else {
			echo "An error has occured\n";
			die("Failed to iterate through...\n");
		}
	}
	return $files_to_move;
}

function move_files_to_directory($files, $target) {
	foreach ($files as $file) {
		$filename = basename($file);
		$destination = $target . '/' . $filename;
		
		if (rename($file, $destination)) {
			echo "Successfully moved \"$file\" to \"$target\"\n";
		} else {
			echo "An error has occured\n";
			die("Failed to move file '$file'...\n");
		}
	}
}

function zip_up_directory($source_directory, $zip_file_name) {
	$zip = new ZipArchive();
	if ($zip->open($zip_file_name, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
		$files = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($source_directory),
			RecursiveIteratorIterator::LEAVES_ONLY
		);

		foreach ($files as $name => $file) {
			if (!$file->isDir()) {
				$file_path = $file->getRealPath();
				$relative_path = substr($file_path, strlen($source_directory) + 1);
				$zip->addFile($file_path, $relative_path);
			}
		}
		$zip->close();
		echo "Directory has been zipped up successfully as $zip_file_name\n";

	} else {
		echo "An error has occured\n";
		die("Failed to create zip archive...\n");
	}
}

function main() {

	$working_directory = '/home/lain/Sandbox';

	if (!is_dir($working_directory)) {
		echo "An error has occured\n";
		die("The directory '$working_directory' does not exist...\n");
	}

	$target_directory = $working_directory . '/' . 'Backup';

	make_a_directory($target_directory);

	$files_to_move = get_files_to_move($working_directory);

	move_files_to_directory($files_to_move, $target_directory);

	$zip_file_name = basename($target_directory) . '.zip';

	zip_up_directory($target_directory, $zip_file_name);
}

main();

?>

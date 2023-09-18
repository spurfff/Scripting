<?php

function get_files_to_move($source_directory) {
	$contents = scandir($source_directory);
	$files_to_move = [];

	foreach ($contents as $file) {
		if ($file !== '.' && $file !== '..') {
			$file_path = $source_directory . '/' . $file;
			if (is_file($file_path)) {
				$files_to_move[] = $file_path;
			} else {
				echo "'$file' is not a file\n";
			}
		}
	}
	return $files_to_move;
}

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



function move_files_to_directory($files, $target) {
	foreach ($files as $file) {
		if (basename($file) !== 'Backup' && basename($file) !== '.donotdelete' && basename($file) !== '.' && basename($file) !== '..' && !is_dir($file)) {
			$filename = basename($file);
			$destination = $target . '/' . $filename;
			
			try {
				if (rename($file, $destination)) {
					echo "Successfully moved \"$file\" to \"$target\"\n";
				} else {
					throw new Exception("Failed to move file '$file'");
				}
			} catch (Exception $e) {
				echo "An error has occurred: " . $e->getMessage() . "\n";
			}
		}
	}
}

function zip_up_directory($source_directory, $zip_file_name, $zip_file_destination) {

	

	$zip = new ZipArchive;
	if ($zip-> open($zip_file_destination, ZipArchive::CREATE ) == TRUE) {
		$dir = opendir($source_directory);
		while ($file = readdir($dir)) {
			if (is_file($source_directory . '/' . $file)) {
				$pathdir = $source_directory . '/' . $file;
				$zip -> addFile($pathdir, $file);
			}
		}
		$zip -> close();
	}
	if (file_exists($zip_file_destination)) {
		echo "Successfully created zip: '$zip_file_destination'\n";
	} else {
		echo "Failed to create: '$zip_file_destination'\n";
		die("Exiting...\n");
	}
}

function main() {

	$working_directory = '/home/lain/Sandbox';

	if (!is_dir($working_directory)) {
		echo "An error has occured\n";
		die("The directory '$working_directory' does not exist...\n");
	}

	$target_directory = $working_directory . '/' . 'Backup';

	$files_to_move = get_files_to_move($working_directory);

	make_a_directory($target_directory);

	move_files_to_directory($files_to_move, $target_directory);

	if (class_exists('ZipArchive')) {
		$zip_file_name = basename($target_directory) . '.zip';
		$zip_file_destination = $working_directory . '/' . $zip_file_name;
		zip_up_directory($target_directory, $zip_file_name, $zip_file_destination);
	} else {
		echo "The ZipArchive class is not available. Unable to create zip archive.\n";
	}
}

main();

?>

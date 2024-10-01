<?php

/**
 * ProgressBar
 * 
 * This class provides a simple command-line progress bar.
 */
class ProgressBar {
    /** @var int The total number of items */
    private $total;

    /** @var int The current progress */
    private $current = 0;

    /** @var int The length of the progress bar */
    private $barLength = 50;

    /**
     * Constructor for ProgressBar
     * 
     * @param string $text The text to display before the progress bar
     * @param int $total The total number of items
     */
    public function __construct(string $text, int $total) {
        $this->total = $total;
        echo $text . ":\n";
        $this->update();
    }

    /**
     * Advance the progress bar
     * 
     * @param int $step The number of steps to advance
     */
    public function advance(int $step = 1): void {
        $this->current += $step;
        $this->update();
    }

    /**
     * Update the display of the progress bar
     */
    private function update(): void {
        $percent = $this->current / $this->total;
        $bar = floor($percent * $this->barLength);
        $statusBar = "\r[";
        $statusBar .= str_repeat("=", $bar);
        if ($bar < $this->barLength) {
            $statusBar .= ">";
            $statusBar .= str_repeat(" ", $this->barLength - $bar - 1);
        } else {
            $statusBar .= "=";
        }
        $statusBar .= "] ";
        $statusBar .= number_format($percent * 100, 0);
        $statusBar .= "%  {$this->current}/{$this->total}";
        echo $statusBar;
        flush();
    }

    /**
     * Finish the progress bar
     */
    public function finish(): void {
        echo "\nDone!\n";
    }

    /**
     * Get the current progress
     * 
     * @return int The current progress
     */
    public function getProgress(): int {
        return $this->current;
    }
}
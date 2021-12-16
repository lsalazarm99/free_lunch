import { FC } from "react";

const LoaderIndicator: FC = () => {
  return (
    <div className="flex items-center justify-center space-x-4 animate-pulse">
      <div className="w-8 h-8 bg-accent rounded-full" />
      <div className="w-8 h-8 bg-accent rounded-full" />
      <div className="w-8 h-8 bg-accent rounded-full" />
    </div>
  );
};

export default LoaderIndicator;
